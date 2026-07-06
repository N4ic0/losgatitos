<?php
namespace App\Http\Controllers;

use App\Models\Feriado;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class FeriadoController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $feriados = Feriado::orderBy('fecha', 'desc')->get();
        return view('admin.feriados.index', compact('feriados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|unique:feriados,fecha',
            'descripcion' => 'required|string|max:255',
        ]);

        $feriado = Feriado::create($request->all());
        $this->auditoriaService->registrar('crear', 'feriados', $feriado->id, null, $feriado->toArray());
        return redirect()->route('admin.feriados.index')->with('success', 'Feriado registrado.');
    }

    public function destroy(Feriado $feriado)
    {
        $this->auditoriaService->registrar('eliminar', 'feriados', $feriado->id, $feriado->toArray(), null);
        $feriado->delete();
        return redirect()->route('admin.feriados.index')->with('success', 'Feriado eliminado.');
    }
}
