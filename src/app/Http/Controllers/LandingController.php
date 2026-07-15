<?php
namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Promocion;
use App\Models\Configuracion;
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
        $habitaciones = Habitacion::where('estado', 'Disponible')->get();
        $promocionActiva = $this->promocionRepository->getActivas()->first();
        return view('landing.reservar', compact('habitaciones', 'promocionActiva'));
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
