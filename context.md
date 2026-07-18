# Los Gatitos - Contexto de Desarrollo

## Stack
- **Backend**: Laravel 11 + MySQL 8 (Docker en WSL)
- **Frontend**: Vite + Tailwind CSS + Bootstrap 5.3 (npm)
- **JS Modules**: jQuery, DataTables 2.x, SweetAlert2, Flowbite, GSAP, AOS, Swiper

## Docker (WSL Ubuntu)
```bash
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan cache:clear
npm run build   # dentro del contenedor o desde WSL con nvm
```
- Node v22.23.1 via nvm (source ~/.nvm/nvm.sh antes de npm)
- Puerto: localhost:8080

## Convenciones SweetAlert2

### Toast (crear/insertar — operaciones exitosas sin confirmación)
```javascript
Swal.fire({
    icon: 'success',
    title: 'Producto creado',
    text: 'Mensaje opcional',
    timer: 2500,
    showConfirmButton: false,
    toast: true,
    position: 'top-end',
});
```

### Alert normal (eliminar, modificar — requiere confirmación del usuario)
```javascript
// Confirmación antes de eliminar
Swal.fire({
    title: '¿Eliminar producto?',
    text: 'Esta acción no se puede deshacer',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#D4AF37',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    background: '#1a1a2e',
    color: '#e5e7eb',
}).then((result) => {
    if (result.isConfirmed) {
        // ejecutar eliminación...
        Swal.fire({
            icon: 'success',
            title: 'Eliminado',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
        });
    }
});
```

### Error
```javascript
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: err.message || 'Mensaje por defecto',
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
});
```

## Modales Bootstrap (Crear/Editar — modal único con modo)

### Patrón: un solo modal con JS que detecta modo por data-*
```html
<button class="accion-editar" data-id="{{ $item->id }}">Editar</button>
<button class="accion-nuevo" data-bs-toggle="modal" data-bs-target="#miModal">Nuevo</button>
```

### JS
```javascript
abrirModal: function(modo, id) {
    var modal = document.getElementById('miModal');
    var title = document.getElementById('miModalTitle');
    var submitBtn = document.getElementById('miModalSubmit');

    form.reset();
    // valores por defecto
    if (modo === 'create') {
        title.textContent = 'Nuevo';
        submitBtn.textContent = 'Crear';
        metodoInput.value = '';
        idInput.value = '';
        bootstrap.Modal.getOrCreateInstance(modal).show();
    } else {
        title.textContent = 'Editar';
        submitBtn.textContent = 'Guardar Cambios';
        metodoInput.value = 'PUT';
        idInput.value = id;
        fetch('/api/item/' + id + '/data')
            .then(r => r.json())
            .then(data => { /* llenar campos */; bootstrap.Modal.getOrCreateInstance(modal).show(); });
    }
}
```

### Mostrar modal
```javascript
bootstrap.Modal.getOrCreateInstance(el).show();
```
- `getOrCreateInstance` es más robusto que `new bootstrap.Modal(el)` porque reusa instancia existente.

### Ocultar modal
```javascript
bootstrap.Modal.getInstance(el)?.hide();
```

## DataTables 2.x (npm: datatables.net-bs5 + datatables.net-responsive-bs5)
- Selectores DT2.x: `div.dt-container`, `div.dt-length`, `div.dt-search`, `div.dt-paging`
- Paginación Bootstrap: `.pagination .page-item .page-link`
- Responsive: `td.dt-control:before` (flecha verde #22c55e)

## CSRF en fetch
```javascript
fetch(url, {
    headers: { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
    body: formData, // FormData o JSON
});
```

## Envío de formularios con archivos (AJAX)
```javascript
var formData = new FormData(form);
formData.set('_token', token);
if (editando) formData.set('_method', 'PUT');
fetch(url, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: formData })
```

## Backend: detección AJAX
```php
$request->ajax()  // true si header X-Requested-With: XMLHttpRequest
```

## Errores de validación (Laravel + AJAX)
Laravel retorna 422 con JSON `{ message: "...", errors: { campo: ["error"] } }`
```javascript
if (!res.ok) {
    return res.json().then(function(err) {
        var msg = err.errors ? Object.values(err.errors).flat().join(', ') : (err.message || 'Error del servidor');
        throw new Error(msg);
    });
}
```
