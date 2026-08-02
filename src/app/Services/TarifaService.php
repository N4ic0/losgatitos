<?php
namespace App\Services;

use App\Models\Tarifa;
use App\Models\Feriado;
use Carbon\Carbon;

class TarifaService
{
    private const HORA_CORTE_DEFECTO = 8;

    public function calcularPrecio(string $categoria, string $fecha, string $tipoTiempo = '8h', int $horasAdicionales = 0, bool $terceraPersona = false): array
    {
        $fechaCarbon = Carbon::parse($fecha);
        
        $tarifa = Tarifa::where('categoria', $categoria)
            ->where('tipo_tiempo', $tipoTiempo)
            ->where('activo', true)
            ->first();
        
        if (!$tarifa) {
            return ['precio_base' => 0, 'horas_adicionales' => 0, 'tercera_persona' => 0, 'total' => 0, 'error' => 'Tarifa no encontrada'];
        }

        // Corte del turno a las 08:00: el día tarifario efectivo se desplaza hacia atrás
        $diaSemana = $this->getDiaSemana($this->diaEfectivo($fechaCarbon, $tarifa));
        
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
                    'viernes' => $tarifaAdicional->precio_viernes,
                    'sabado' => $tarifaAdicional->precio_sabado,
                    'vispera' => $tarifaAdicional->precio_vispera ?? $tarifaAdicional->precio_dj,
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
            'tarifa_id' => $tarifa?->id,
        ];
    }

    private function diaEfectivo(Carbon $fecha, ?Tarifa $tarifa): Carbon
    {
        if ($fecha->isSameDay(now())) {
            $fecha = now();
        }

        $horaCorte = self::HORA_CORTE_DEFECTO;
        if ($tarifa && $tarifa->hora_inicio) {
            $horaCorte = Carbon::parse($tarifa->hora_inicio)->hour;
        }

        return $fecha->hour < $horaCorte ? $fecha->copy()->subDay() : $fecha;
    }

    private function getDiaSemana(Carbon $fecha): string
    {
        $manana = $fecha->copy()->addDay()->startOfDay();
        $esVispera = Feriado::where('fecha', $manana->format('Y-m-d'))->exists();
        
        if ($esVispera) {
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
