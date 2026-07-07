<?php
namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Reserva;
use App\Services\OcupacionService;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct(
        private OcupacionService $ocupacionService
    ) {}

    public function dashboard()
    {
        $data = $this->ocupacionService->getDashboardData();
        $reservasHoy = Reserva::with('habitacion')
            ->whereDate('fecha', today())
            ->orderBy('hora')
            ->get();

        return view('admin.dashboard.index', array_merge($data, [
            'reservasHoy' => $reservasHoy,
        ]));
    }
}
