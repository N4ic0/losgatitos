<?php
namespace App\Services;

use App\Models\Tarifa;
use App\Models\Feriado;
use Carbon\Carbon;

class TarifaService
{
    public function calcularPrecio(string $categoria, string $fecha, string $tipoTiempo = '8h', int $horasAdicionales = 0, bool $terceraPersona = false): array
    {
        $fechaCarbon = Carbon::parse($fecha);
        $diaSemana = $this->getDiaSemana($fechaCarbon);
        
        $tarifa = Tarifa::where('categoria', $categoria)
            ->where('tipo_tiempo', $tipoTiempo)
            ->where('activo', true)
            ->first();
        
        if (!$tarifa) {
            return ['precio_base' => 0, 'horas_adicionales' => 0, 'tercera_persona' => 0, 'total' => 0, 'error' => 'Tarifa no encontrada'];
        }
        
        $precioBase = match($diaSemana) {
            'viernes' => $tarifa->precio_viernes,
            'sabado' => $tarifa->precio_sabado,
            'vispera' => $tarifa->precio_vispera ?? $tarifa->precio_dj,
            default => $tarifa->precio_dj,
        };
        
        $costoAdicional = 0;
        if ($horasAdicionales > 0) {
            $tarifaAdicional = Tarifa::where('categoria', $categoria)
                ->where('tipo_tiempo', 'Hora adicional')
                ->where('activo', true)
                ->first();
            
            if ($tarifaAdicional) {
                $precioHora = match($diaSemana) {
                    'viernes', 'sabado' => $tarifaAdicional->precio_viernes,
                    default => $tarifaAdicional->precio_dj,
                };
                $costoAdicional = $precioHora * $horasAdicionales;
            }
        }
        
        $costoTerceraPersona = 0;
        if ($terceraPersona) {
            $costoTerceraPersona = intval($precioBase * 0.5);
        }
        
        $promocionActiva = $this->verificarPromocionAutomatica($fechaCarbon);
        
        $total = $precioBase + $costoAdicional + $costoTerceraPersona;
        
        return [
            'precio_base' => $precioBase,
            'horas_adicionales' => $costoAdicional,
            'tercera_persona' => $costoTerceraPersona,
            'total' => $total,
            'promocion' => $promocionActiva,
            'dia_semana' => $diaSemana,
        ];
    }

    private function getDiaSemana(Carbon $fecha): string
    {
        $esFeriado = Feriado::where('fecha', $fecha->format('Y-m-d'))->exists();
        
        if ($esFeriado) {
            return 'vispera';
        }
        
        $dia = $fecha->dayOfWeek;
        return match($dia) {
            Carbon::FRIDAY => 'viernes',
            Carbon::SATURDAY => 'sabado',
            default => 'dj',
        };
    }

    private function verificarPromocionAutomatica(Carbon $fecha): ?array
    {
        $promocion = \App\Models\Promocion::where('activo', true)
            ->where('fecha_inicio', '<=', $fecha)
            ->where('fecha_fin', '>=', $fecha)
            ->first();
        
        return $promocion ? $promocion->toArray() : null;
    }
}
