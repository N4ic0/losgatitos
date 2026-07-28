<?php
namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Promocion;
use App\Models\Configuracion;
use App\Models\Feriado;
use App\Services\TarifaService;
use App\Repositories\PromocionRepository;

class LandingController extends Controller
{
    public function __construct(
        private TarifaService $tarifaService,
        private PromocionRepository $promocionRepository
    ) {}

    public function index()
    {
        $promocionActiva = $this->promocionRepository->getActivas()->first();
        $habitaciones = Habitacion::whereIn('estado', ['Disponible'])->get();
        return view('landing.index', compact('promocionActiva', 'habitaciones'));
    }

    public function habitaciones()
    {
        $habitaciones = Habitacion::orderBy('numero')->get();
        return view('landing.habitaciones', compact('habitaciones'));
    }

    public function promociones()
    {
        $promociones = $this->promocionRepository->getActivas();
        return view('landing.promociones', compact('promociones'));
    }

    public function contacto()
    {
        $config = Configuracion::pluck('valor', 'clave')->toArray();
        return view('landing.contacto', compact('config'));
    }

    public function reservar()
    {
        return redirect('/')->with('info', 'Las reservas no están disponibles en este momento.');
    }

    public function verificarReserva()
    {
        $hoy = now();
        $dia = $hoy->dayOfWeek;
        $esFinde = $dia === 5 || $dia === 6;
        $manana = $hoy->copy()->addDay();
        $esVispera = Feriado::whereDate('fecha', $manana)->exists();
        $permitida = !$esFinde && !$esVispera;

        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        if (!$permitida) {
            $mensaje = 'Solo puedes realizar reservas de <strong>Domingo a Jueves</strong>';
            if ($esVispera) {
                $mensaje .= ', y mañana es <strong>víspera de feriado</strong>';
            }
            $mensaje .= '.<br><br>Hoy (<strong>' . $dias[$dia] . '</strong>) las reservas <strong>no están disponibles</strong>.<br>Vuelve a intentarlo otro día.';
        } else {
            $mensaje = '';
        }

        return response()->json([
            'permitida' => $permitida,
            'mensaje' => $mensaje,
        ]);
    }

    public function disponibilidad()
    {
        $suites = Habitacion::where('categoria', 'Suite')->where('estado', 'Disponible')->count();
        $departamentos = Habitacion::where('categoria', 'Departamento')->where('estado', 'Disponible')->count();
        return response()->json([
            'suites' => $suites,
            'departamentos' => $departamentos,
        ]);
    }

    public function calcularPrecio(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'categoria' => 'required|in:Suite,Departamento',
            'fecha' => 'required|date',
            'horas_adicionales' => 'integer|min:0',
            'tercera_persona' => 'boolean',
        ]);

        $precios = $this->tarifaService->calcularPrecio(
            $request->categoria,
            $request->fecha,
            '8h',
            $request->horas_adicionales ?? 0,
            $request->boolean('tercera_persona')
        );

        return response()->json($precios);
    }
}
