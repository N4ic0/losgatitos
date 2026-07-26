<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KioskController;

Route::prefix('kiosco')->group(function () {
    Route::get('ping', [KioskController::class, 'ping']);

    Route::middleware(['auth:sanctum', 'abilities:kiosk'])->group(function () {
        Route::get('disponibilidad', [KioskController::class, 'disponibilidad']);
        Route::post('reserva', [KioskController::class, 'validarReserva']);
        Route::post('asignar', [KioskController::class, 'asignar']);
        Route::post('habitacion/estado', [KioskController::class, 'cambiarEstado']);
    });
});
