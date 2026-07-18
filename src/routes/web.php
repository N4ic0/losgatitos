<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\PromocionProductoController;
use App\Http\Controllers\FeriadoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\OcupacionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Landing pages
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/habitaciones', [LandingController::class, 'habitaciones'])->name('landing.habitaciones');
Route::get('/promociones', [LandingController::class, 'promociones'])->name('landing.promociones');
Route::get('/contacto', [LandingController::class, 'contacto'])->name('landing.contacto');
Route::get('/reservar', [LandingController::class, 'reservar'])->name('landing.reservar');
Route::post('/reservar', [ReservaController::class, 'store'])->name('landing.reservar.store');
Route::post('/calcular-precio', [LandingController::class, 'calcularPrecio'])->name('landing.calcular-precio');

// Dashboard redirect (Breeze compatibility)
Route::get('/dashboard', function () {
    return redirect('/admin');
})->name('dashboard');

// Admin panel
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Rooms
    Route::resource('habitaciones', HabitacionController::class)->parameters(['habitaciones' => 'habitacion'])->except(['show']);

    // Reservations
    Route::resource('reservas', ReservaController::class)->parameters(['reservas' => 'reserva']);
    Route::post('reservas/{reserva}/asignar', [ReservaController::class, 'asignarHabitacion'])->name('reservas.asignar');
    Route::post('reservas/{reserva}/liberar', [ReservaController::class, 'liberar'])->name('reservas.liberar');
    Route::post('reservas/{reserva}/cobrar-horas', [ReservaController::class, 'cobrarHoras'])->name('reservas.cobrar-horas');
    Route::post('habitaciones/{habitacion}/cambiar-estado', [ReservaController::class, 'cambiarEstado'])->name('habitaciones.cambiar-estado');
    Route::get('buscar-por-rut', [ReservaController::class, 'buscarPorRUT'])->name('reservas.buscar-rut');

    // Rates
    Route::get('tarifas-json', [TarifaController::class, 'data'])->name('tarifas.data');
    Route::get('tarifas/{tarifa}/data', [TarifaController::class, 'getJson'])->name('tarifas.getJson');
    Route::post('tarifas/{tarifa}/toggle', [TarifaController::class, 'toggle'])->name('tarifas.toggle');
    Route::resource('tarifas', TarifaController::class)->only(['index', 'edit', 'update']);

    // Promotions
    Route::get('promociones-json', [PromocionController::class, 'data'])->name('promociones.data');
    Route::get('promociones/{promocion}/data', [PromocionController::class, 'getJson'])->name('promociones.getJson');
    Route::resource('promociones', PromocionController::class)->parameters(['promociones' => 'promocion'])->except(['create', 'edit']);

    // Product combos (promocion_producto)
    Route::resource('promocion-productos', PromocionProductoController::class)->parameters(['promocion-productos' => 'promocionProducto']);

    // Products
    Route::get('productos-json', [ProductoController::class, 'data'])->name('productos.data');
    Route::post('productos/{producto}/toggle', [ProductoController::class, 'toggle'])->name('productos.toggle');
    Route::get('productos/catalogo', [ProductoController::class, 'catalogo'])->name('productos.catalogo');
    Route::get('productos/{producto}/data', [ProductoController::class, 'getJson'])->name('productos.getJson');
    Route::resource('productos', ProductoController::class)->parameters(['productos' => 'producto']);

    // Stock ingresos
    Route::post('ingresos', [IngresoController::class, 'store'])->name('ingresos.store');

    // Categories (AJAX)
    Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');

    // Occupations
    Route::resource('ocupaciones', OcupacionController::class)->parameters(['ocupaciones' => 'ocupacion'])->only(['index', 'show', 'destroy']);

    // Roles
    Route::resource('roles', RoleController::class)->parameters(['roles' => 'role']);

    // Users
    Route::resource('usuarios', UserController::class)->parameters(['usuarios' => 'user']);

    // Holidays
    Route::resource('feriados', FeriadoController::class)->only(['index', 'store', 'destroy']);
    Route::post('feriados/importar', [FeriadoController::class, 'importar'])->name('feriados.importar');

    // Dashboard AJAX
    Route::get('dashboard/calcular-tarifa', [DashboardController::class, 'calcularTarifa'])->name('dashboard.calcular-tarifa');
    Route::get('dashboard/habitacion/{habitacion}', [DashboardController::class, 'datosHabitacion'])->name('dashboard.habitacion');
    Route::post('dashboard/habitacion/{habitacion}/cambiar-estado', [DashboardController::class, 'cambiarEstado'])->name('dashboard.cambiar-estado');
    Route::post('dashboard/habitacion/{habitacion}/iniciar-ocupacion', [DashboardController::class, 'iniciarOcupacion'])->name('dashboard.iniciar-ocupacion');
    Route::get('dashboard/ocupacion/{ocupacion}', [DashboardController::class, 'datosOcupacion'])->name('dashboard.ocupacion');
    Route::post('dashboard/ocupacion/{ocupacion}/cliente', [DashboardController::class, 'registrarCliente'])->name('dashboard.registrar-cliente');
    Route::post('dashboard/ocupacion/{ocupacion}/consumo', [DashboardController::class, 'agregarConsumo'])->name('dashboard.agregar-consumo');
    Route::post('dashboard/ocupacion/{ocupacion}/consumos-batch', [DashboardController::class, 'agregarConsumosBatch'])->name('dashboard.agregar-consumos-batch');
    Route::post('dashboard/ocupacion/{ocupacion}/cortesia', [DashboardController::class, 'agregarCortesia'])->name('dashboard.agregar-cortesia');
    Route::put('dashboard/consumo/{consumo}', [DashboardController::class, 'actualizarConsumo'])->name('dashboard.actualizar-consumo');
    Route::delete('dashboard/consumo/{consumo}', [DashboardController::class, 'eliminarConsumo'])->name('dashboard.eliminar-consumo');
    Route::post('dashboard/ocupacion/{ocupacion}/pago', [DashboardController::class, 'registrarPago'])->name('dashboard.registrar-pago');
    Route::post('dashboard/ocupacion/{ocupacion}/finalizar', [DashboardController::class, 'finalizarOcupacion'])->name('dashboard.finalizar-ocupacion');
    Route::post('dashboard/ocupacion/{ocupacion}/vehiculo', [DashboardController::class, 'actualizarVehiculo'])->name('dashboard.actualizar-vehiculo');
    Route::post('dashboard/ocupacion/{ocupacion}/personas-adicionales', [DashboardController::class, 'actualizarPersonasAdicionales'])->name('dashboard.actualizar-personas-adicionales');
    Route::post('dashboard/ocupacion/{ocupacion}/observacion', [DashboardController::class, 'agregarObservacion'])->name('dashboard.agregar-observacion');
    Route::get('dashboard/productos', [DashboardController::class, 'productos'])->name('dashboard.productos');
    Route::get('dashboard/promociones', [DashboardController::class, 'promociones'])->name('dashboard.promociones');
    Route::post('dashboard/ocupacion/{ocupacion}/tomar-promocion/{promocion}', [DashboardController::class, 'tomarPromocion'])->name('dashboard.tomar-promocion');
    Route::post('dashboard/ocupacion/{ocupacion}/productos-promocion', [DashboardController::class, 'agregarProductosPromocion'])->name('dashboard.productos-promocion');

    // Profile
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil');
});

require __DIR__.'/auth.php';
