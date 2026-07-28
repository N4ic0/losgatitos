#!/usr/bin/env python3
"""
Kiosco Motel Los Gatitos - Raspberry Pi 5
Flujo autónomo con botón físico + voz (Vosk STT + Piper TTS)
"""

import os
import sys
import json
import time
import subprocess
import threading
import queue
from pathlib import Path

import requests
import RPi.GPIO as GPIO
import sounddevice as sd
import numpy as np
import vosk
import wave
import argparse

# ─── CONFIG ───────────────────────────────────────────────────────────
API_BASE = os.getenv("API_BASE", "http://192.168.100.142:8080")
API_TOKEN = os.getenv("API_TOKEN", "REEMPLAZA_CON_TU_TOKEN")
HEADERS = {"Authorization": f"Bearer {API_TOKEN}", "Accept": "application/json"}

BUTTON_PIN = 17       # GPIO17 (físico) para activar
LED_PIN = 27          # GPIO27 - LED indicador de espera
RELAY_PIN = 22        # GPIO22 - relay del portón

MODEL_DIR = Path.home() / "kiosko" / "models"
VOSK_MODEL = str(MODEL_DIR / "vosk-model-small-es-0.42")
PIPER_MODEL = str(MODEL_DIR / "voz.onnx")

SAMPLE_RATE = 16000
LISTEN_SECONDS = 5
TIMEOUT_IDLE = 10     # segundos sin actividad antes de reiniciar
# ──────────────────────────────────────────────────────────────────────

vosk.SetLogLevel(-1)
modelo_voz = None
recognizer = None
audio_queue = queue.Queue()


# ─── AUDIO ────────────────────────────────────────────────────────────

PALABRAS_CUSTOM = ["suite", "suit", "si", "no", "si tengo", "no tengo",
                   "correcto", "incorrecto", "equis"]


def normalizar(texto):
    """Corrige errores comunes del reconocimiento de voz."""
    remplazos = {
        "soy": "suite", "sui": "suite", "suid": "suite",
        "suiche": "suite", "suit": "suite",
        "si ten go": "si tengo", "si ten": "si tengo",
        "no ten go": "no tengo", "no ten": "no tengo",
        "equis": "x", "k": "k",
        "correcta": "correcto", "cocierto": "correcto",
    }
    for mal, bien in remplazos.items():
        texto = texto.replace(mal, bien)
    return texto


def iniciar_vosk():
    global modelo_voz, recognizer
    if not os.path.exists(VOSK_MODEL):
        print(f"[VOSK] Modelo no encontrado en {VOSK_MODEL}")
        return False
    modelo_voz = vosk.Model(VOSK_MODEL)
    recognizer = vosk.KaldiRecognizer(modelo_voz, SAMPLE_RATE)
    for palabra in PALABRAS_CUSTOM:
        recognizer.SetWords(True)
    print("[VOSK] Modelo cargado")
    return True


def callback_audio(indata, frames, time_info, status):
    if status:
        print(f"[AUDIO] {status}")
    audio_queue.put(bytes(indata))


def escuchar(tiempo=LISTEN_SECONDS) -> str:
    """Escucha y transcribe. Retorna texto normalizado en minúsculas."""
    if not recognizer:
        return ""
    with sd.RawInputStream(samplerate=SAMPLE_RATE, blocksize=8000,
                           device=None, dtype="int16",
                           channels=1, callback=callback_audio):
        print("[MICRO] Escuchando...")
        sd.sleep(int(tiempo * 1000))

    while not audio_queue.empty():
        data = audio_queue.get()
        recognizer.AcceptWaveform(data)
    result = json.loads(recognizer.FinalResult())
    texto = result.get("text", "").strip().lower()
    if texto:
        texto = normalizar(texto)
        print(f"[TRANSCRIPCION] {texto}")
    return texto


def hablar(texto):
    """Sintetiza voz con Piper TTS."""
    texto = texto.replace(" suite ", " suit ").replace(" suites ", " suits ")
    texto = texto.replace("Suite", "Suit").replace("Suites", "Suits")
    if not os.path.exists(PIPER_MODEL):
        print(f"[TTS] {texto}")
        return
    print(f"[TTS] {texto}")
    subprocess.run(
        ["piper", "--model", PIPER_MODEL, "--output-raw"],
        input=texto.encode("utf-8"),
        stdout=subprocess.PIPE, stderr=subprocess.DEVNULL
    )
    subprocess.run(["aplay", "-r", "22050", "-f", "S16_LE", "-c", "1"],
                   stdin=subprocess.PIPE)


# ─── GPIO ─────────────────────────────────────────────────────────────

def setup_gpio():
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(BUTTON_PIN, GPIO.IN, pull_up_down=GPIO.PUD_UP)
    GPIO.setup(LED_PIN, GPIO.OUT)
    GPIO.setup(RELAY_PIN, GPIO.OUT)
    GPIO.output(LED_PIN, GPIO.HIGH)
    GPIO.output(RELAY_PIN, GPIO.LOW)
    print("[GPIO] Configurado")


def led_parpadear(interval=0.3):
    for _ in range(6):
        GPIO.output(LED_PIN, not GPIO.input(LED_PIN))
        time.sleep(interval)
    GPIO.output(LED_PIN, GPIO.HIGH)


def abrir_porton():
    print("[RELAY] Abriendo portón...")
    GPIO.output(RELAY_PIN, GPIO.HIGH)
    time.sleep(3)
    GPIO.output(RELAY_PIN, GPIO.LOW)
    print("[RELAY] Portón cerrado")


def esperar_boton():
    """Bloquea hasta presionar el botón (flanco descendente)."""
    print("[BOTON] Esperando pulsación...")
    GPIO.output(LED_PIN, GPIO.HIGH)
    GPIO.wait_for_edge(BUTTON_PIN, GPIO.FALLING)
    time.sleep(0.05)
    print("[BOTON] Pulsado!")
    led_parpadear()


# ─── API ──────────────────────────────────────────────────────────────

def api_ping():
    try:
        r = requests.get(f"{API_BASE}/api/kiosco/ping", headers=HEADERS, timeout=5)
        return r.status_code == 200
    except Exception as e:
        print(f"[API] Error ping: {e}")
        return False


def api_disponibilidad():
    try:
        r = requests.get(f"{API_BASE}/api/kiosco/disponibilidad", headers=HEADERS, timeout=5)
        if r.status_code == 200:
            return r.json()
    except Exception as e:
        print(f"[API] Error disponibilidad: {e}")
    return {"suites": [], "departamentos": []}


def api_validar_reserva(rut):
    try:
        r = requests.post(f"{API_BASE}/api/kiosco/reserva",
                          headers=HEADERS, json={"rut": rut}, timeout=5)
        return r.status_code == 200, r.json()
    except Exception as e:
        print(f"[API] Error validar reserva: {e}")
        return False, {}


def api_asignar(reserva_id, habitacion_id):
    try:
        r = requests.post(f"{API_BASE}/api/kiosco/asignar",
                          headers=HEADERS,
                          json={"reserva_id": reserva_id, "habitacion_id": habitacion_id},
                          timeout=5)
        return r.status_code == 200, r.json()
    except Exception as e:
        print(f"[API] Error asignar: {e}")
        return False, {}


# ─── HELPERS ──────────────────────────────────────────────────────────

MAPEO_NUMEROS = {
    "cero": "0", "uno": "1", "dos": "2", "tres": "3", "cuatro": "4",
    "cinco": "5", "seis": "6", "siete": "7", "ocho": "8", "nueve": "9",
    "diez": "10", "once": "11", "doce": "12", "trece": "13",
    "catorce": "14", "quince": "15", "dieciseis": "16", "diecisiete": "17",
    "dieciocho": "18", "diecinueve": "19", "veinte": "20",
    "veintiuno": "21", "veintidos": "22", "veintitres": "23",
    "veinticuatro": "24", "veinticinco": "25", "veintiseis": "26",
    "veintisiete": "27", "veintiocho": "28", "veintinueve": "29",
    "treinta": "30"
}


def palabras_a_numeros(texto):
    for palabra, digito in MAPEO_NUMEROS.items():
        texto = texto.replace(palabra, digito)
    return texto


def limpiar_y_validar_rut(texto):
    texto = texto.lower().strip()
    texto = texto.replace("á", "a").replace("é", "e").replace("í", "i")
    texto = texto.replace("ó", "o").replace("ú", "u")
    texto = texto.replace("guion", "-").replace("guión", "-")
    texto = palabras_a_numeros(texto)
    solo_validos = []
    for c in texto:
        if c.isdigit() or c in "Kk.-":
            solo_validos.append(c)
    texto = "".join(solo_validos)
    partes = texto.replace("-", ".").split(".")
    partes = [p for p in partes if p]
    if not partes:
        return "", "No entendí el RUT."
    if partes[-1].upper() == "K" and len(partes) > 1:
        cuerpo = "".join(partes[:-1])
        dv = "K"
    else:
        cuerpo = "".join(partes[:-1]) if len(partes) > 1 else partes[0]
        dv = partes[-1] if len(partes) > 1 else ""
    cuerpo = "".join(c for c in cuerpo if c.isdigit())
    dv = "".join(c for c in dv if c.isdigit() or c == "K")
    if not cuerpo or len(cuerpo) < 6:
        return "", "El RUT parece muy corto."
    return f"{cuerpo}-{dv}", f"{int(cuerpo):,}-{dv}".replace(",", ".")


def verificar_disponibilidad():
    try:
        r = requests.get(f"{API_BASE}/verificar-reserva", timeout=5)
        return r.json()
    except Exception as e:
        print(f"[API] Error verificar: {e}")
        return {"permitida": False, "mensaje": "Error de conexión"}


# ─── FLUJO PRINCIPAL ──────────────────────────────────────────────────

def flujo_bienvenida():
    check = verificar_disponibilidad()
    if not check.get("permitida", False):
        hablar(check.get("mensaje", "Hoy no hay reservas disponibles."))
        return

    hablar("Bienvenido al Motel Los Gatitos. ¿Tienes una reserva?")
    print("[FLUJO] ¿Tiene reserva? (decí 'sí' o 'no')")
    texto = escuchar(4)

    if "si" in texto or "sí" in texto:
        flujo_con_reserva()
    else:
        flujo_sin_reserva()


def flujo_con_reserva():
    hablar("Por favor, decí tu RUT en voz alta.")
    print("[FLUJO] Esperando RUT por voz...")
    for intento in range(3):
        texto = escuchar(6)
        if not texto:
            hablar("No te escuché bien. Intenta de nuevo.")
            continue
        rut_limpio, rut_formateado = limpiar_y_validar_rut(texto)
        if not rut_limpio:
            hablar(rut_formateado + " Intenta de nuevo.")
            continue
        hablar(f"Dijiste RUT {rut_formateado}, ¿es correcto?")
        confirmacion = escuchar(3)
        if confirmacion and ("si" in confirmacion or "sí" in confirmacion
                             or "correcto" in confirmacion):
            break
        hablar("OK, intentemos otra vez.")
    else:
        hablar("No pude entender el RUT después de varios intentos. "
               "Presiona el botón para empezar de nuevo.")
        return
    ok, data = api_validar_reserva(rut_limpio)
    if not ok or not data.get("valido"):
        hablar(data.get("mensaje", "No se encontró ninguna reserva para ese RUT."))
        return
    reserva = data["reserva"]
    hab_id = reserva.get("habitacion_id")
    hab_num = reserva.get("habitacion_numero", "")
    if hab_id:
        ok_asig, _ = api_asignar(reserva["id"], hab_id)
        if ok_asig:
            hablar(f"Reserva confirmada a nombre de {reserva.get('nombre', '')}. "
                   f"Tu habitación es la número {hab_num}. El portón se va a abrir.")
            abrir_porton()
            return
    hablar(f"Reserva lista para {reserva.get('nombre', '')}. "
           f"Presiona el botón cuando llegues.")
    print(f"[FLUJO] Reserva: hab {hab_num}")


def flujo_sin_reserva():
    hablar("Déjame consultar la disponibilidad.")
    disp = api_disponibilidad()
    suites = disp.get("suites", [])
    deptos = disp.get("departamentos", [])

    if not suites and not deptos:
        hablar("Lo siento, no hay habitaciones disponibles en este momento.")
        return

    if suites and deptos:
        desde_s = min(s["tarifa"] for s in suites)
        desde_d = min(d["tarifa"] for d in deptos)
        hablar(f"Hay {len(deptos)} departamentos desde {desde_d} pesos. "
               f"Y {len(suites)} suites desde {desde_s} pesos. "
               "Decí 1 para departamento o 2 para suite.")
        print("[FLUJO] Esperando opción (1=depto, 2=suite)...")
        texto = escuchar(4)
        if "1" in texto or "uno" in texto or "departamento" in texto:
            seleccion = deptos
            tipo = "departamento"
        elif "2" in texto or "dos" in texto or "suite" in texto or "suit" in texto:
            seleccion = suites
            tipo = "suite"
        else:
            hablar("No entendí. Presiona el botón para empezar de nuevo.")
            return
    elif suites:
        seleccion = suites
        tipo = "suite"
        hablar(f"Tenemos {len(suites)} suites disponibles desde "
               f"{min(s['tarifa'] for s in suites)} pesos.")
    else:
        seleccion = deptos
        tipo = "departamento"
        hablar(f"Tenemos {len(deptos)} departamentos disponibles desde "
               f"{min(d['tarifa'] for d in deptos)} pesos.")

    hablar(f"Elegiste {tipo}. Acércate a recepción para continuar.")
    print(f"[FLUJO] Selección: {tipo}, {len(seleccion)} disponibles")


# ─── MAIN LOOP ────────────────────────────────────────────────────────

def loop_principal():
    print("=" * 50)
    print("Kiosco Los Gatitos - Una ejecución por ciclo")
    print("Presioná el botón físico para activar")
    print("=" * 50)

    try:
        if not api_ping():
            hablar("El sistema no está disponible. Contacta al administrador.")
            return

        esperar_boton()
        flujo_bienvenida()
        hablar("Gracias por tu visita. Que tengas una excelente estadía.")
        print("[LOOP] Cliente atendido. El programa ha finalizado.")

    except Exception as e:
        print(f"[ERROR] {e}")


if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument("--test-voz", action="store_true", help="Prueba de micrófono")
    parser.add_argument("--test-ping", action="store_true", help="Prueba de conexión API")
    parser.add_argument("--test-button", action="store_true", help="Prueba de botón")
    parser.add_argument("--no-button", action="store_true", help="Sin botón físico (modo SSH / solo voz)")
    parser.add_argument("--test-rut", type=str, help="Simula RUT para probar (sin micrófono)")
    args = parser.parse_args()

    if args.test_voz:
        iniciar_vosk()
        hablar("Prueba de altavoz.")
        time.sleep(1)
        print("Decí algo...")
        print(f"> {escuchar(4)}")
        sys.exit(0)

    if args.test_ping:
        print(f"Ping a {API_BASE}... ", end="")
        print("OK" if api_ping() else "FALLO")
        sys.exit(0)

    if args.test_button:
        setup_gpio()
        print("Presioná el botón...")
        GPIO.wait_for_edge(BUTTON_PIN, GPIO.FALLING)
        print("Botón detectado!")
        GPIO.cleanup()
        sys.exit(0)

    if args.no_button:
        print("[MODO] Sin botón - solo voz, ideal para SSH")
        iniciar_vosk()
        try:
            if not api_ping():
                print("[ERROR] Sistema no disponible")
                sys.exit(1)
            flujo_bienvenida()
            print("[OK] Cliente atendido. Listo.")
        finally:
            sys.exit(0)

    setup_gpio()
    iniciar_vosk()

    try:
        loop_principal()
    finally:
        GPIO.cleanup()
        print("[GPIO] Limpiado")
