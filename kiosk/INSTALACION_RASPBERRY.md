# Recrear la "caja" (Kiosco Raspberry Pi)

Guía verificada para reconstruir el disco de la Raspberry del kiosco.
**Modelo:** Raspberry Pi 5, **SO:** Ubuntu Server 24.04 (arm64).

---

## 1. Instalar el sistema operativo

1. Descargar **Raspberry Pi Imager** (`https://www.raspberrypi.com/software/`).
2. Elegir **Ubuntu Server 24.04 LTS (arm64)** para Raspberry Pi 5.
3. Grabar en microSD o en un disco USB/NVMe (el Pi 5 arranca de USB/NVMe sin adaptadores).
4. En el Imager preconfigurar:
   - Usuario: `naico`
   - Activar **SSH**
   - WiFi (SSID y contraseña) si no se usará cable
5. Arrancar y conectarse: `ssh naico@<ip_de_la_pi>`

> Importante para el Pi 5: los GPIO del header viven en `/dev/gpiochip4`
> (y **no** en gpiochip0). `RPi.GPIO` los lee directamente sin sudo.

---

## 2. Sistema base + dependencias

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y \
  git curl tar \
  ffmpeg alsa-utils libasound2-dev portaudio19-dev \
  python3-venv python3-pip
```

Conectar el **audio USB** (micrófono + parlante). Verificar:

```bash
aplay -l     # salida de reproducción (card 0 = USB Audio)
arecord -l   # micrófonos de entrada
```

---

## 3. Tailscale (red remota estable)

```bash
curl -fsSL https://tailscale.com/install.sh | sh
sudo tailscale up                      # autorizar desde el panel de Tailscale
tailscale ip -4                        # ejemplo: 100.x.y.z  → IP fija de la caja
```

Desde otra PC: `ssh naico@100.x.y.z` aunque cambie de red/localidad.
Habilitar en el arranque: `sudo systemctl enable --now tailscaled` (ya lo deja).

---

## 4. Entorno Python (venv) en ~/kiosko

```bash
mkdir -p ~/kiosko/models
cd ~/kiosko
python3 -m venv ~/kiosko
~/kiosko/bin/pip install --upgrade pip

~/kiosko/bin/pip install vosk==0.3.45
~/kiosko/bin/pip install piper-tts==1.6.0     # instala el binario 'piper'
~/kiosko/bin/pip install sounddevice==0.5.5
~/kiosko/bin/pip install numpy requests
~/kiosko/bin/pip install RPi.GPIO
~/kiosko/bin/pip install onnxruntime
```

Los binarios del venv (incluido `piper`) quedan en `/home/naico/kiosko/bin/`.

---

## 5. Modelos de voz

Dentro de `~/kiosko/models`:

```bash
cd ~/kiosko/models
# STT - Vosk pequeño en español (descomprimir aquí):
mkdir -p vosk
tar -xzf vosk-model-small-es-0.42.tar.gz -C vosk --strip-components=1
# enlace que espera kiosko.py:
ln -sfn vosk vosk-model-small-es-0.42

# TTS - Piper (voz "es_MX-claude-high"):
# el archivo .onnx + .onnx.json se colocan aquí, el activo se llama votz.onnx:
ln -sf es_MX-claude-high.onnx voz.onnx
ln -sf es_MX-claude-high.onnx.json voz.onnx.json
```

Los nombres exactos que lee el código:

| Constante      | Archivo esperado                         |
|----------------|------------------------------------------|
| `VOSK_MODEL`   | `~/kiosko/models/vosk-model-small-es-0.42` |
| `PIPER_MODEL`  | `~/kiosko/models/voz.onnx`               |

---

## 6. Código del kiosco

Copiar `kiosk/kiosko.py` del repo a `~/kiosko/kiosko.py`.

Config que define el archivo (no hay necesidad de tocar: se puede vía env):
- `API_BASE=https://motellosgatitos.cl`
- `API_TOKEN=<token>` (por defecto viene el valor trabajado)
- **Código maestro de apertura directa:** `4849`

Pines (se configuran como constantes `BCM`, ya definidos en el código):

| GPIO | Función                          |
|------|----------------------------------|
| 4    | Timbre del teclado (arranca ciclo) |
| 27   | LED indicador de espera          |
| 22   | Relay del portón                 |
| 23   | Wiegand D0 (verde)               |
| 24   | Wiegand D1 (blanco)              |

---

## 7. GPIO del relay / no disparar el portón al encender

En el Pi 5 la config del boot está en `/boot/firmware/config.txt`.
Agregar al final estas dos líneas:

```
gpio=22=out,dl
gpiopull=22=down
```

Eso mantiene GPIO22 en LOW durante el arranque hasta que el servicio lo controle
(evita que el portón se abra al encender). **Reiniciar y comprobar.**

---

## 8. Servicio systemd automático

Crear `/etc/systemd/system/kiosko.service`:

```ini
[Unit]
Description=Eliseo Motel Los Gatitos
Wants=network-online.target
After=network-online.target sound.target

[Service]
Type=simple
User=naico
WorkingDirectory=/home/naico/kiosko
Environment=PYTHONUNBUFFERED=1
Environment=PATH=/home/naico/kiosko/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
Environment=XDG_RUNTIME_DIR=/run/user/1000
ExecStart=/home/naico/kiosko/bin/python /home/naico/kiosko/kiosko.py
Restart=always
RestartSec=5
KillSignal=SIGINT
TimeoutStopSec=30

[Install]
WantedBy=multi-user.target
```

Habilitar y manejar:

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now kiosko
sudo systemctl status kiosko
journalctl -u kiosko -f
```

---

## 9. Comandos de diagnóstico

```bash
journalctl -u kiosko -f                                   # ver el flujo en vivo
/home/naico/kiosko/bin/python -m py_compile /home/naico/kiosko/kiosko.py  # compilar
aplay -l                                                   # audio de salida
arecord -l                                                 # micro de entrada
tailscale status                                           # IP y nodo remoto
```

Logs que aparecen en el journal: `[TIMBRE]`, `[KEYPAD]`, `[CODIGO]`,
`[RELAY]`, `[FLUJO]`, `[TTS]`, `[GPIO]`, `[VOSK]`.

---

## 10. Hardware (cápsula de referencia)

- Raspberry Pi 5 + microSD/USB con Ubuntu Server 24.04 arm64.
- **Teclado Wiegand** (12 V, con botón de campana):
  - D0 (verde) → GPIO23
  - D1 (blanco) → GPIO24
  - Timbre (señal) → GPIO4 (open-collector, PUD_UP interno)
  - Timbre común (rosado 2) → **GND de la Pi (pin 9)**
  - Negativo de los 12 V también compartido (puente a GND de la Pi)
- **Relé del portón:** señal → GPIO22, 5V → pin 2, GND → pin 6.
- **LED indicador:** GPIO27.
- Audio: micrfo USB de entrada + salida por USB/ALSA (`aplay`).

## Copia de seguridad rápida de la caja

El archivo de código vive en: `/home/naico/kiosko/kiosko.py`
En el repo local de trabajo: `kiosk/kiosko.py` (y `kiosk/kiosko.py.bak_codigo_4849`).