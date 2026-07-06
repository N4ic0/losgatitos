<?php
namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Reserva;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $habitaciones = Habitacion::with('reservaActiva')->orderBy('numero')->get();
        $reservasHoy = Reserva::with('habitacion')
            ->whereDate('fecha', today())
            ->orderBy('hora')
            ->get();

        $ocupadas = $habitaciones->where('estado', 'Ocupada')->count();
        $disponibles = $habitaciones->where('estado', 'Disponible')->count();
        $reservadas = $habitaciones->where('estado', 'Reservada')->count();
        $limpieza = $habitaciones->where('estado', 'Limpieza')->count();

        return view('admin.dashboard.index', compact(
            'habitaciones', 'reservasHoy', 'ocupadas', 'disponibles', 'reservadas', 'limpieza'
        ));
    }
}
