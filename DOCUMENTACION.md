# Motel Los Gatitos — Documentación del Proyecto

## Stack tecnológico

- **Backend**: Laravel 13 + PHP 8.4 + MySQL 8.0 (Docker en WSL2)
- **Frontend**: Vite + Tailwind CSS + Bootstrap 5.3 (npm)
- **JS Modules**: jQuery, DataTables 2.x, SweetAlert2, Flowbite, GSAP, AOS, Swiper
- **Infraestructura**: Docker Compose, Nginx, WSL2 (Ubuntu)

## Arquitectura general

```
Laravel 13 API (Docker)
        |
        | HTTP REST API
        |
   Raspberry Pi 5
```

### Backend (Laravel 13)

Responsabilidades:
- Administración de habitaciones y control de estados
- Gestión de reservas y tarifas dinámicas
- Validación de clientes (RUT)
- Registro de movimientos y auditoría
- API para dispositivos externos (Raspberry Pi)

### Hardware Raspberry Pi 5

| Componente | Detalle |
|---|---|
| RAM | 8GB |
| SO | Ubuntu Server 24.04 LTS ARM64 |
| Teclado | K1-MF (ingreso RUT, selección de opciones) |
| Micrófono | Genius MIC-100U USB |
| Parlante | Salida de audio para comunicación por voz |
| Control acceso | Relay 4 canales conectado a GPIO |

Regla especial: la letra **K** del RUT se reemplaza automáticamente por **1**.
Ejemplo: `12.345.678-K` → `123456781`

## Docker

Ejecutar comandos desde `D:\desarrollo\contenedores\losgatitos\`:

```bash
docker compose up -d
docker compose exec app php artisan view:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan optimize:clear
```

- Node v22.23.1 via nvm (ejecutar `source ~/.nvm/nvm.sh` antes de npm)
- Puerto local: `localhost:8080`
- Puerto LAN: `http://192.168.100.142:8080`
- DB: `localhost:3308` (user: `laravel`, pass: `secret`, db: `laravel`)

## API — Endpoints del Kiosco

### Estado del sistema

```
GET /api/kiosco/ping
```
```json
{ "status": "ok" }
```

### Disponibilidad

```
GET /api/kiosco/disponibilidad
```
```json
{
  "departamentos": [{ "numero": 101, "tarifa": 45000 }],
  "suites": []
}
```

### Validar reserva

```
POST /api/kiosco/reserva
{ "rut": "123456781" }
```

### Cambiar estado de habitación

```
POST /api/kiosco/habitacion/estado
```

Estados: `DISPONIBLE`, `RESERVADA`, `TRANSITO`, `OCUPADA`, `LIMPIEZA`

## Restricciones comerciales

Las reservas aplican solo **domingo a jueves** (no viernes, sábado, feriados ni vísperas). La validación la realiza Laravel, la Raspberry solo consulta.

## Flujo principal del sistema

### Caso 1: Cliente con reserva

1. Bienvenida → ¿Tiene reserva? → Sí
2. Solicita RUT → Cliente ingresa por teclado
3. API valida la reserva y obtiene habitación
4. Sistema informa: "Su habitación es la número XX"
5. Activa relé → abre portón
6. Actualiza estado de la habitación a `TRANSITO`

### Caso 2: Cliente sin reserva

1. Bienvenida → ¿Tiene reserva? → No
2. Consulta disponibilidad a la API
3. Sistema informa opciones disponibles con tarifas
4. Cliente selecciona
5. Sistema asigna habitación y abre portón
6. Actualiza estado a `TRANSITO`

## Convenciones de desarrollo

### SweetAlert2

**Toast** (crear/insertar — operaciones sin confirmación):
```javascript
Swal.fire({ icon: 'success', title: 'Producto creado', timer: 2500,
  showConfirmButton: false, toast: true, position: 'top-end' });
```

**Alert normal** (eliminar/modificar — requiere confirmación):
```javascript
Swal.fire({ title: '¿Eliminar?', text: 'No se puede deshacer', icon: 'warning',
  showCancelButton: true, confirmButtonColor: '#D4AF37',
  cancelButtonColor: '#6b7280', confirmButtonText: 'Sí, eliminar',
  cancelButtonText: 'Cancelar', background: '#1a1a2e', color: '#e5e7eb'
}).then((r) => { if (r.isConfirmed) { /* ejecutar */ } });
```

**Error**:
```javascript
Swal.fire({ icon: 'error', title: 'Error', text: err.message,
  toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
```

### Modales Bootstrap (Crear/Editar — modal único con modo)

```javascript
abrirModal: function(modo, id) {
  form.reset();
  if (modo === 'create') {
    title.textContent = 'Nuevo'; submitBtn.textContent = 'Crear';
    metodoInput.value = ''; idInput.value = '';
    bootstrap.Modal.getOrCreateInstance(modal).show();
  } else {
    title.textContent = 'Editar'; submitBtn.textContent = 'Guardar Cambios';
    metodoInput.value = 'PUT'; idInput.value = id;
    fetch('/api/item/' + id + '/data').then(r => r.json()).then(data => {
      /* llenar campos */; bootstrap.Modal.getOrCreateInstance(modal).show();
    });
  }
}
```

- Mostrar: `bootstrap.Modal.getOrCreateInstance(el).show()`
- Ocultar: `bootstrap.Modal.getInstance(el)?.hide()`

### DataTables 2.x

- Selectores: `div.dt-container`, `div.dt-length`, `div.dt-search`, `div.dt-paging`
- Paginación: `.pagination .page-item .page-link`
- Responsive: `td.dt-control:before` (flecha verde `#22c55e`)

### CSRF en fetch

```javascript
fetch(url, {
  headers: { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
  body: formData,
});
```

Backend: `$request->ajax()` → `true` si header `X-Requested-With: XMLHttpRequest`.

### Formularios con archivos (AJAX)

```javascript
var formData = new FormData(form);
formData.set('_token', token);
if (editando) formData.set('_method', 'PUT');
fetch(url, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest',
  'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: formData });
```

### Errores de validación (Laravel + AJAX)

Laravel retorna `422` con JSON `{ message: "...", errors: { campo: ["error"] } }`:

```javascript
if (!res.ok) {
  return res.json().then(function(err) {
    var msg = err.errors ? Object.values(err.errors).flat().join(', ')
      : (err.message || 'Error del servidor');
    throw new Error(msg);
  });
}
```

## API — Autenticación

La API usa **Laravel Sanctum** con tokens de acceso.

### Generar token

```bash
# Dentro del contenedor Laravel
docker exec laravel_app php artisan kiosco:token

# Para asignar a un usuario específico
docker exec laravel_app php artisan kiosco:token {user_id}
```

Usar el token en cada request:
```http
Authorization: Bearer {token}
```

### Estados de habitación en el kiosco

El endpoint `POST /api/kiosco/habitacion/estado` acepta estos estados:

| Estado Kiosco | Estado BD | Descripción |
|---|---|---|
| `DISPONIBLE` | Disponible | Habitación libre |
| `RESERVADA` | Reservada | Bloqueada por reserva |
| `TRANSITO` | Ocupada | Cliente en tránsito (crea automáticamente la ocupación y abre portón) |
| `OCUPADA` | Ocupada | Cambio manual a ocupada |
| `LIMPIEZA` | Limpieza | En proceso de limpieza |

## Seguridad

- La Raspberry no tiene acceso directo a la base de datos
- Todo acceso es mediante API con tokens de autenticación (Sanctum)
- Solo tokens con habilidad `kiosk` pueden usar la API del kiosco
- Se registran eventos y auditoría de acciones

## Raspberry Pi — Configuración de Audio

### Parlante Bluetooth BLIK-LIVE

| Componente | Estado |
|---|---|
| MAC | `B9:1F:85:99:44:3A` |
| PulseAudio sink | `bluez_sink.B9_1F_85_99_44_3A.a2dp_sink` |
| Auto-conexión | `bt-speaker.service` (systemd) |
| PulseAudio service | systemd user service (`pulseaudio.service`) |

### Micrófono USB

| Componente | Detalle |
|---|---|
| Dispositivo | `MUSIC-BOOST USB Microphone` (ID: `1b3f:0004`) |
| PulseAudio source | `alsa_input.usb-MUSIC-BOOST_USB_Microphone_MB-306-00.mono-fallback` |

### Motores de IA (offline)

| Motor | Propósito | Modelo |
|---|---|---|
| **Vosk** | Reconocimiento de voz (STT) | `vosk-model-small-es-0.42` (español) |
| **Piper TTS** | Síntesis de voz | `es_ES-carlfm-x_low` (español, femenino) |

### Script principal

```bash
/home/naico/kiosko/kiosko.py
```

Flujo: bienvenida → ¿tiene reserva? → (sí) ingresa RUT → API valida → asigna habitación → abre portón / (no) API disponibilidad → informa opciones.

### Probar componentes manualmente

```bash
# Activar entorno
source /home/naico/kiosko/bin/activate

# TTS
echo "Hola mundo" | piper --model models/voz.onnx -f test.wav

# STT (escucha 5s y reconoce)
python3 -c "import sounddevice as sd, numpy as np; print(sd.query_devices())"

# API
curl -s -H "Authorization: Bearer TOKEN" http://192.168.100.142:8080/api/kiosco/ping
```

## Desarrollo por etapas

| Etapa | Estado | Descripción |
|---|---|---|
| 1 | ✅ | Raspberry instalada con Ubuntu Server 24.04, Python, comunicación LAN |
| 2 | ✅ | Endpoints del kiosco creados, Sanctum instalado, token generado, probado desde Raspberry Pi |
| 3 | ✅ | Micrófono USB configurado, BLIK-LIVE conectado (Bluetooth + PulseAudio + auto-conexión), Piper TTS + Vosk instalados, script de bienvenida funcional |
| 4 | 🔄 | Reconocimiento de voz (Vosk) + TTS (Piper) + flujo de bienvenida básico implementado. Pendiente: refinar detección, manejo de errores, integración completa con teclado |
| 5 | ⬜ | Integrar teclado, GPIO, control de relé, apertura de portón |

## Objetivo final

Sistema autónomo de recepción para Motel Los Gatitos capaz de atender clientes sin intervención humana, con integración completa al sistema Laravel existente y preparado para futuras ampliaciones mediante inteligencia artificial.
