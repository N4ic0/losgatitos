<?php
namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Habitacion;
use App\Services\ReservaService;
use App\Repositories\ReservaRepository;
use App\Http\Requests\StoreReservaRequest;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function __construct(
        private ReservaService $reservaService,
        private ReservaRepository $reservaRepository
    ) {}

    public function index()
    {
        $reservas = $this->reservaRepository->getAll();
        return view('admin.reservas.index', compact('reservas'));
    }

    public function create()
    {
        $habitaciones = Habitacion::where('estado', 'Disponible')->get();
        return view('admin.reservas.create', compact('habitaciones'));
    }

    public function store(StoreReservaRequest $request)
    {
        $data = $request->validated();
        $data['estado'] = 'Reservada';

        $existente = Reserva::where('rut', $data['rut'])
            ->whereDate('fecha', $data['fecha'])
            ->whereIn('estado', ['Reservada', 'Ingresada'])
            ->with('habitacion')
            ->first();

        if ($existente) {
            return response()->json([
                'error' => true,
                'mensaje' => "Ya tienes reservada la habitación N° {$existente->habitacion?->numero} para esta fecha.",
                'reserva_existente' => $existente,
            ], 409);
        }

        $reserva = $this->reservaService->crearReserva($data);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Reserva creada exitosamente.',
                'reserva' => $reserva,
            ], 201);
        }

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva creada exitosamente.');
    }

    public function show(Reserva $reserva)
    {
        $reserva->load('habitacion', 'user');
        return view('admin.reservas.show', compact('reserva'));
    }

    public function edit(Reserva $reserva)
    {
        $habitaciones = Habitacion::all();
        return view('admin.reservas.edit', compact('reserva', 'habitaciones'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        $reserva->update($request->all());
        return redirect()->route('admin.reservas.index')->with('success', 'Reserva actualizada.');
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return redirect()->route('admin.reservas.index')->with('success', 'Reserva eliminada.');
    }

    public function asignarHabitacion(Request $request, Reserva $reserva)
    {
        $request->validate(['habitacion_id' => 'required|exists:habitaciones,id']);
        $this->reservaService->asignarHabitacion($reserva->id, $request->habitacion_id);
        return redirect()->back()->with('success', 'Habitación asignada exitosamente.');
    }

    public function liberar(Reserva $reserva)
    {
        $this->reservaService->liberarHabitacion($reserva->id);
        return redirect()->back()->with('success', 'Habitación liberada exitosamente.');
    }

    public function cambiarEstado(Request $request, Habitacion $habitacion)
    {
        $request->validate(['estado' => 'required|in:Disponible,Reservada,Ocupada,Limpieza,Mantenimiento']);
        $this->reservaService->cambiarEstadoHabitacion($habitacion->id, $request->estado);
        return redirect()->back()->with('success', 'Estado actualizado.');
    }

    public function cobrarHoras(Request $request, Reserva $reserva)
    {
        $request->validate(['horas' => 'required|integer|min:1']);
        $resultado = $this->reservaService->cobrarHorasAdicionales($reserva->id, $request->horas);
        return redirect()->back()->with('success', "Se cobraron {$request->horas} hora(s) adicional(es) por \${$resultado['cobro']}.");
    }

    public function buscarPorRUT(Request $request)
    {
        $request->validate(['rut' => 'required|string']);
        $reservas = $this->reservaService->buscarPorRUT($request->rut);
        return response()->json($reservas);
    }
}
