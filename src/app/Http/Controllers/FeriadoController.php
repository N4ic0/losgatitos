<?php
namespace App\Http\Controllers;

use App\Models\Feriado;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function importar()
    {
        $response = Http::get('https://api.boostr.cl/holidays.json');

        if ($response->failed()) {
            return redirect()->route('admin.feriados.index')
                ->with('error', 'No se pudo conectar con feriados.cl.');
        }

        $feriados = $response->json('data', []);
        $creados = 0;
        $actualizados = 0;

        foreach ($feriados as $item) {
            $feriado = Feriado::where('fecha', $item['date'])->first();

            if ($feriado) {
                if ($feriado->descripcion !== $item['title']) {
                    $feriado->update(['descripcion' => $item['title']]);
                    $actualizados++;
                }
            } else {
                Feriado::create([
                    'fecha' => $item['date'],
                    'descripcion' => $item['title'],
                ]);
                $creados++;
            }
        }

        $mensaje = "Importación completada. {$creados} feriados creados";
        if ($actualizados > 0) {
            $mensaje .= ", {$actualizados} actualizados";
        }
        $mensaje .= '.';

        return redirect()->route('admin.feriados.index')->with('success', $mensaje);
    }
}
