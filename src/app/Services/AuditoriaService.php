<?php
namespace App\Services;

use App\Models\Auditorium;
use Illuminate\Support\Facades\Request;

class AuditoriaService
{
    public function registrar(string $accion, string $tabla, ?int $registroId = null, $datosAntiguos = null, $datosNuevos = null): Auditorium
    {
        return Auditorium::create([
            'user_id' => auth()->id(),
            'accion' => $accion,
            'tabla' => $tabla,
            'registro_id' => $registroId,
            'datos_antiguos' => $datosAntiguos ? json_encode($datosAntiguos) : null,
            'datos_nuevos' => $datosNuevos ? json_encode($datosNuevos) : null,
            'ip' => request()->ip(),
        ]);
    }
}
