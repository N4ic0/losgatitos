#!/usr/bin/env python3
"""
Test interactivo de sensibilidad del micrófono para el Kiosco Los Gatitos.

Mide el nivel de entrada en dBFS en tiempo real y permite ajustar la
ganancia del micro (ALSA + PulseAudio) para calibrar la sensibilidad.

Uso (en la caja, como usuario naico):
    cd ~/kiosko
    ./bin/python test_micro.py            # medir nivel + ajustar ganancia
    ./bin/python test_micro.py --live      # prueba de reconocimiento (decí 1/2)
    ./bin/python test_micro.py --probe     # solo mostrar dispositivos y niveles

Controles durante la medición:
    g+ / g-   subir/bajar ganancia ALSA (Mic Capture Volume)
    p+ / p-   subir/bajar volumen PulseAudio
    r         re-scanear niveles de entrada (sin ajustar)
    q         salir
"""

import argparse
import sys
import time
import queue
import subprocess
import numpy as np

try:
    import sounddevice as sd
    import vosk
except ImportError:
    print("Faltan dependencias. ¿Estás en el venv? Usá: ./bin/python test_micro.py")
    sys.exit(1)

SAMPLE_RATE = 16000
ALSA_CARD = 1           # card1 = MUSIC-BOOST USB Microphone MB-306
PULSE_SOURCE = "alsa_input.usb-MUSIC-BOOST_USB_Microphone_MB-306-00.mono-fallback"

# estado de la ganancia (para no re-leer a cada paso)
estado = {"alsa": None, "pulse": None}


def leer_alsa():
    """Lee Mic Capture Volume de la card 1. Devuelve (valor, limite, en_porcentaje)."""
    try:
        out = subprocess.run(["amixer", "-c", str(ALSA_CARD), "sget", "Mic"],
                             capture_output=True, text=True).stdout
        for line in out.splitlines():
            if "Mono:" in line or "Capture:" in line:
                parts = line.strip().split()
                # ej: "Mono: Capture 23 [77%] [22.50dB] [on]"
                val = parts[2] if len(parts) > 2 else None
                for i, p in enumerate(parts):
                    if "[" in p and "%" in p:
                        pct = p.strip("[]%")
                        return int(val), int(pct)
    except Exception:
        pass
    return None, None


def set_alsa(pct):
    """Ajusta Mic Capture Volume de la card 1 a un porcentaje dado."""
    try:
        subprocess.run(["amixer", "-c", str(ALSA_CARD), "sset", "Mic",
                        f"{int(pct)}%", "on"], capture_output=True, text=True)
    except Exception:
        pass


def leer_pulse():
    """Lee el volumen PulseAudio del source. Devuelve (volumen_int, porcentaje)."""
    try:
        out = subprocess.run(["pactl", "get-source-volume", PULSE_SOURCE],
                             capture_output=True, text=True).stdout
        for line in out.splitlines():
            if "mono:" in line:
                parts = line.split()
                for i, p in enumerate(parts):
                    if p.startswith("mono:"):
                        vol = parts[i + 1].rstrip("/") if i + 1 < len(parts) else None
                        for j, q in enumerate(parts[i + 1:]):
                            if "%" in q:
                                pct = q.strip("[]%")
                                return int(vol), int(pct)
    except Exception:
        pass
    return None, None


def set_pulse(pct):
    """Ajusta el volumen PulseAudio del source a un porcentaje dado."""
    try:
        subprocess.run(["pactl", "set-source-volume", PULSE_SOURCE, f"{int(pct)}%"],
                       capture_output=True, text=True)
    except Exception:
        pass


def medir_nivel(segundos=2.0):
    """Graba N segundos y devuelve (pico_dbfs, rms_dbfs). Negativo = más bajo."""
    try:
        with sd.RawInputStream(samplerate=SAMPLE_RATE, blocksize=8000,
                               dtype="int16", channels=1) as stream:
            frames = []
            fin = time.time() + segundos
            while time.time() < fin:
                data, overflow = stream.read(4000)
                arr = np.frombuffer(data, dtype=np.int16)
                if arr.size:
                    frames.append(arr.copy())
        arr = np.concatenate(frames) if frames else np.array([], dtype=np.int16)
        if arr.size == 0:
            return None, None
        pico = np.max(np.abs(arr))
        rms = np.sqrt(np.mean(arr.astype(np.float64) ** 2))
        pico_db = 20 * np.log10((pico + 1e-12) / 32767)
        rms_db = 20 * np.log10((rms + 1e-12) / 32767)
        return pico_db, rms_db
    except Exception as e:
        return None, str(e)


def mostrar_barra(db, ancho=40):
    """Dibuja una barra visual del nivel dBFS."""
    if db is None:
        return "n/d"
    # rango -60 a 0 dB
    frac = max(0.0, min(1.0, (db + 60) / 60))
    fill = int(frac * ancho)
    return "#" * fill + "." * (ancho - fill)


def init_vosk():
    modelo = "/home/naico/kiosko/models/vosk-model-small-es-0.42"
    import os
    if not os.path.exists(modelo):
        print("Modelo Vosk no encontrado:", modelo)
        return None
    return vosk.KaldiRecognizer(vosk.Model(modelo), SAMPLE_RATE)


def modo_live():
    """Prueba de reconocimiento continuo con palabras clave del kiosko."""
    rec = init_vosk()
    if not rec:
        return
    import re
    # palabras que el kiosko interpreta como "uno" o "dos"
    UNO_RE = re.compile(r"\b(uno|uno dos|uno d|una|un)\b", re.IGNORECASE)
    DOS_RE = re.compile(r"\b(dos|dos uno|do s|do)\b", re.IGNORECASE)
    print("\n=== Modo LIVE (palabras del kiosko) ===")
    print("Hablá 'uno' o 'dos' al micro. El test marca OK si interpreta tu elección.")
    print("Ctrl+C para salir.")
    try:
        with sd.RawInputStream(samplerate=SAMPLE_RATE, blocksize=8000,
                               dtype="int16", channels=1) as stream:
            print("Escuchando...")
            while True:
                data, overflow = stream.read(4000)
                if rec.AcceptWaveform(bytes(data)):
                    res = rec.Result()
                    import json
                    txt = json.loads(res).get("text", "").strip()
                    if not txt:
                        continue
                    uno = UNO_RE.search(txt)
                    dos = DOS_RE.search(txt)
                    if uno and dos:
                        marca = "UNO y DOS (ambos)"
                    elif uno:
                        marca = "UNO"
                    elif dos:
                        marca = "DOS"
                    else:
                        marca = f"otro: '{txt}'"
                    print(f"  detectado '{txt}' -> {marca}", flush=True)
    except KeyboardInterrupt:
        print("\nFin del test.")
    except Exception as e:
        print("Error:", e)


def mostrar_estado():
    a_val, a_pct = leer_alsa()
    p_val, p_pct = leer_pulse()
    estado["alsa"], estado["pulse"] = a_pct, p_pct
    print(f"  ALSA Mic Volume : {a_pct}%" if a_pct is not None else "  ALSA: n/d")
    print(f"  Pulse volume    : {p_pct}%" if p_pct is not None else "  Pulse: n/d")


def modo_medir():
    print("=== Medición de nivel del micrófono ===")
    print("Hablá al micro mientras mirás el pico. Controles: g+/g- = ALSA, p+/p- = Pulse, r = releer, q = salir")
    mostrar_estado()
    while True:
        pico_db, rms_db = medir_nivel(1.0)
        if isinstance(rms_db, str):
            print(f"  Error: {rms_db}")
            time.sleep(1)
            continue
        linea = f"  Pico {pico_db:6.1f} dB  |  RMS {rms_db:6.1f} dB  |  {mostrar_barra(pico_db)}"
        sys.stdout.write("\r" + linea)
        sys.stdout.flush()

        # lectura de teclado no bloqueante (solo Linux con select)
        import select
        if select.select([sys.stdin], [], [], 0.2)[0]:
            tecla = sys.stdin.readline().strip().lower()
            if tecla == "q":
                print()
                break
            elif tecla == "g+" or tecla == "g":
                set_alsa(min(100, (estado.get("alsa") or 0) + 10))
                print("\n  subiendo ALSA...")
                mostrar_estado()
            elif tecla == "g-":
                set_alsa(max(0, (estado.get("alsa") or 0) - 10))
                print("\n  bajando ALSA...")
                mostrar_estado()
            elif tecla == "p+":
                set_pulse(min(150, (estado.get("pulse") or 0) + 10))
                print("\n  subiendo Pulse...")
                mostrar_estado()
            elif tecla == "p-":
                set_pulse(max(0, (estado.get("pulse") or 0) - 10))
                print("\n  bajando Pulse...")
                mostrar_estado()
            elif tecla == "r":
                print("\n  re-leyendo...")
                mostrar_estado()


def modo_probe():
    print("=== Dispositivos de audio ===")
    print("Default:", sd.default.device)
    print(sd.query_devices())
    print("\n=== Niveles actuales ===")
    mostrar_estado()


if __name__ == "__main__":
    ap = argparse.ArgumentParser(description="Test de sensibilidad del micro")
    ap.add_argument("--live", action="store_true", help="prueba de reconocimiento en vivo")
    ap.add_argument("--probe", action="store_true", help="solo info de dispositivos")
    args = ap.parse_args()

    if args.live:
        modo_live()
    elif args.probe:
        modo_probe()
    else:
        modo_medir()
