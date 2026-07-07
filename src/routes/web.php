<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\FeriadoController;
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
    Route::resource('tarifas', TarifaController::class)->only(['index', 'edit', 'update']);

    // Promotions
    Route::resource('promociones', PromocionController::class)->parameters(['promociones' => 'promocion']);

    // Holidays
    Route::resource('feriados', FeriadoController::class)->only(['index', 'store', 'destroy']);
    Route::post('feriados/importar', [FeriadoController::class, 'importar'])->name('feriados.importar');

    // Dashboard AJAX
    Route::get('dashboard/habitacion/{habitacion}', [DashboardController::class, 'datosHabitacion'])->name('dashboard.habitacion');
    Route::post('dashboard/habitacion/{habitacion}/cambiar-estado', [DashboardController::class, 'cambiarEstado'])->name('dashboard.cambiar-estado');
    Route::post('dashboard/habitacion/{habitacion}/iniciar-ocupacion', [DashboardController::class, 'iniciarOcupacion'])->name('dashboard.iniciar-ocupacion');
    Route::get('dashboard/ocupacion/{ocupacion}', [DashboardController::class, 'datosOcupacion'])->name('dashboard.ocupacion');
    Route::post('dashboard/ocupacion/{ocupacion}/cliente', [DashboardController::class, 'registrarCliente'])->name('dashboard.registrar-cliente');
    Route::post('dashboard/ocupacion/{ocupacion}/consumo', [DashboardController::class, 'agregarConsumo'])->name('dashboard.agregar-consumo');
    Route::post('dashboard/ocupacion/{ocupacion}/pago', [DashboardController::class, 'registrarPago'])->name('dashboard.registrar-pago');
    Route::post('dashboard/ocupacion/{ocupacion}/finalizar', [DashboardController::class, 'finalizarOcupacion'])->name('dashboard.finalizar-ocupacion');
    Route::post('dashboard/ocupacion/{ocupacion}/observacion', [DashboardController::class, 'agregarObservacion'])->name('dashboard.agregar-observacion');
    Route::get('dashboard/productos', [DashboardController::class, 'productos'])->name('dashboard.productos');
    Route::get('dashboard/promociones', [DashboardController::class, 'promociones'])->name('dashboard.promociones');

    // Profile
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil');
});

require __DIR__.'/auth.php';
