<?php
namespace App\Services;

use App\Models\Feriado;
use App\Models\Habitacion;
use App\Models\Ocupacion;
use App\Models\HistorialEstado;
use App\Models\Cliente;
use App\Models\Consumo;
use App\Models\Pago;
use App\Models\Observacione;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\Tarifa;
use Carbon\Carbon;

class OcupacionService
{
    public function __construct(
        private AuditoriaService $auditoriaService,
        private TarifaService $tarifaService
    ) {}

    public function cambiarEstado(Habitacion $habitacion, string $nuevoEstado, ?int $ocupacionId = null): array
    {
        $estadoAnterior = $habitacion->estado;

        $this->cerrarEstadoActual($habitacion);

        $historial = HistorialEstado::create([
            'habitacion_id' => $habitacion->id,
            'estado' => $nuevoEstado,
            'fecha_inicio' => now(),
            'user_id' => auth()->id(),
            'ocupacion_id' => $ocupacionId,
        ]);

        $habitacion->update(['estado' => $nuevoEstado]);

        $this->auditoriaService->registrar(
            'cambio_estado',
            'habitaciones',
            $habitacion->id,
            ['estado' => $estadoAnterior],
            ['estado' => $nuevoEstado]
        );

        return [
            'historial' => $historial,
            'estado_anterior' => $estadoAnterior,
        ];
    }

    public function cerrarEstadoActual(Habitacion $habitacion): void
    {
        HistorialEstado::where('habitacion_id', $habitacion->id)
            ->whereNull('fecha_fin')
            ->update(['fecha_fin' => now()]);
    }

    public function iniciarOcupacion(Habitacion $habitacion, string $tipoTiempo = '8h', int $personasAdicionales = 0): Ocupacion
    {
        $this->cerrarEstadoActual($habitacion);

        $precios = $this->tarifaService->calcularPrecio($habitacion->categoria, now()->format('Y-m-d'), $tipoTiempo);

        $tarifaId = null;
        if (isset($precios['tarifa_id'])) {
            $tarifaId = $precios['tarifa_id'];
        } else {
            $tarifa = Tarifa::where('categoria', $habitacion->categoria)
                ->where('tipo_tiempo', $tipoTiempo)
                ->where('activo', true)
                ->first();
            $tarifaId = $tarifa?->id;
        }

        $precioBase = ($precios['precio_base'] ?? 0) + (int)round(($precios['precio_base'] ?? 0) * 0.5 * $personasAdicionales);

        $ocupacion = Ocupacion::create([
            'habitacion_id' => $habitacion->id,
            'tarifa_id' => $tarifaId,
            'precio_base' => $precioBase,
            'personas_adicionales' => $personasAdicionales,
            'fecha_inicio' => now(),
            'promocion_id' => null,
            'horas_beneficio' => 0,
        ]);

        $this->cambiarEstado($habitacion, 'Ocupada', $ocupacion->id);

        $this->auditoriaService->registrar('iniciar_ocupacion', 'ocupaciones', $ocupacion->id, null, $ocupacion->toArray());

        return $ocupacion->fresh();
    }

    public function actualizarPersonasAdicionales(Ocupacion $ocupacion, int $cantidad): Ocupacion
    {
        $precios = $this->tarifaService->calcularPrecio(
            $ocupacion->habitacion->categoria,
            $ocupacion->fecha_inicio->format('Y-m-d'),
            $ocupacion->tarifa?->tipo_tiempo ?? '8h'
        );

        $precioBase = ($precios['precio_base'] ?? 0) + (int)round(($precios['precio_base'] ?? 0) * 0.5 * $cantidad);

        $ocupacion->update([
            'personas_adicionales' => $cantidad,
            'precio_base' => $precioBase,
        ]);

        return $ocupacion->fresh();
    }

    public function finalizarOcupacion(Ocupacion $ocupacion): void
    {
        $ocupacion->update(['fecha_fin' => now()]);
        $this->cambiarEstado($ocupacion->habitacion, 'Limpieza');

        $this->auditoriaService->registrar('finalizar_ocupacion', 'ocupaciones', $ocupacion->id, null, $ocupacion->toArray());
    }

    public function registrarCliente(Ocupacion $ocupacion, array $data): Cliente
    {
        $cliente = Cliente::updateOrCreate(
            [
                'tipo_documento' => $data['tipo_documento'],
                'numero_documento' => $data['numero_documento'],
            ],
            [
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'nacionalidad' => $data['nacionalidad'] ?? 'Chilena',
                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
            ]
        );

        $ocupacion->clientes()->syncWithoutDetaching([$cliente->id]);

        $this->auditoriaService->registrar('registrar_cliente', 'clientes', $cliente->id, null, $cliente->toArray());

        return $cliente;
    }

    public function agregarConsumo(Ocupacion $ocupacion, int $productoId, int $cantidad): Consumo
    {
        $producto = Producto::findOrFail($productoId);
        $total = $producto->precio * $cantidad;

        $producto->decrement('stock_actual', $cantidad);

        $consumo = Consumo::create([
            'ocupacion_id' => $ocupacion->id,
            'producto_id' => $producto->id,
            'cantidad' => $cantidad,
            'precio_unitario' => $producto->precio,
            'total' => $total,
            'origen' => 'Consumo',
            'user_id' => auth()->id(),
        ]);

        $this->auditoriaService->registrar('registrar_consumo', 'consumos', $consumo->id, null, $consumo->toArray());

        return $consumo;
    }

    public function agregarConsumosBatch(Ocupacion $ocupacion, array $items, bool $cortesia = false): array
    {
        $consumos = [];
        foreach ($items as $item) {
            if ($cortesia) {
                $consumos[] = $this->agregarCortesia($ocupacion, $item['producto_id'], $item['cantidad']);
            } else {
                $consumos[] = $this->agregarConsumo($ocupacion, $item['producto_id'], $item['cantidad']);
            }
        }
        return $consumos;
    }

    public function actualizarConsumo(Consumo $consumo, int $nuevaCantidad): Consumo
    {
        $producto = $consumo->producto;
        $diferencia = $nuevaCantidad - $consumo->cantidad;

        if ($diferencia > 0) {
            $producto->decrement('stock_actual', $diferencia);
        } elseif ($diferencia < 0) {
            $producto->increment('stock_actual', abs($diferencia));
        }

        $precioUnitario = $consumo->precio_unitario;
        $consumo->update([
            'cantidad' => $nuevaCantidad,
            'total' => $precioUnitario * $nuevaCantidad,
        ]);

        return $consumo->fresh();
    }

    public function eliminarConsumo(Consumo $consumo): void
    {
        $producto = $consumo->producto;
        $producto->increment('stock_actual', $consumo->cantidad);
        $consumo->delete();
    }

    public function agregarCortesia(Ocupacion $ocupacion, int $productoId, int $cantidad): Consumo
    {
        $producto = Producto::findOrFail($productoId);

        $producto->decrement('stock_actual', $cantidad);

        $consumo = Consumo::create([
            'ocupacion_id' => $ocupacion->id,
            'producto_id' => $producto->id,
            'cantidad' => $cantidad,
            'precio_unitario' => 0,
            'total' => 0,
            'origen' => 'Consumo',
            'user_id' => auth()->id(),
        ]);

        $this->auditoriaService->registrar('registrar_consumo_cortesia', 'consumos', $consumo->id, null, $consumo->toArray());

        return $consumo;
    }

    public function registrarPago(Ocupacion $ocupacion, int $monto, string $formaPago): Pago
    {
        $pago = Pago::create([
            'ocupacion_id' => $ocupacion->id,
            'monto' => $monto,
            'forma_pago' => $formaPago,
            'user_id' => auth()->id(),
        ]);

        $this->auditoriaService->registrar('registrar_pago', 'pagos', $pago->id, null, $pago->toArray());

        return $pago;
    }

    public function tomarPromocion(Ocupacion $ocupacion, Promocion $promocion): array
    {
        $ocupacion->update([
            'promocion_id' => $promocion->id,
            'horas_beneficio' => $promocion->horas_beneficio,
        ]);

        $this->auditoriaService->registrar('tomar_promocion', 'ocupaciones', $ocupacion->id, null, [
            'promocion_id' => $promocion->id,
            'horas_beneficio' => $promocion->horas_beneficio,
        ]);

        return $this->getDatosOcupacion($ocupacion->fresh());
    }

    public function agregarProductosPromocion(Ocupacion $ocupacion, Promocion $promocion): array
    {
        if (!$ocupacion->promocion_id) {
            $ocupacion->update([
                'promocion_id' => $promocion->id,
                'horas_beneficio' => $promocion->horas_beneficio,
            ]);
            $this->auditoriaService->registrar('tomar_promocion', 'ocupaciones', $ocupacion->id, null, [
                'promocion_id' => $promocion->id,
                'horas_beneficio' => $promocion->horas_beneficio,
            ]);
        }

        if ($promocion->productos()->exists()) {
            foreach ($promocion->productos as $producto) {
                $cantidad = $producto->pivot->cantidad ?? 1;
                $this->agregarConsumoPromocion($ocupacion, $producto, $cantidad);
            }
        }

        return $this->getDatosOcupacion($ocupacion->fresh());
    }

    private function agregarConsumoPromocion(Ocupacion $ocupacion, Producto $producto, int $cantidad): Consumo
    {
        $total = $producto->precio * $cantidad;

        $consumo = Consumo::create([
            'ocupacion_id' => $ocupacion->id,
            'producto_id' => $producto->id,
            'cantidad' => $cantidad,
            'precio_unitario' => $producto->precio,
            'total' => $total,
            'origen' => 'Promocion',
            'user_id' => auth()->id(),
        ]);

        $this->auditoriaService->registrar('registrar_consumo_promocion', 'consumos', $consumo->id, null, $consumo->toArray());

        return $consumo;
    }

    public function agregarObservacion(Ocupacion $ocupacion, string $contenido): Observacione
    {
        return Observacione::create([
            'ocupacion_id' => $ocupacion->id,
            'contenido' => $contenido,
            'user_id' => auth()->id(),
        ]);
    }

    public function getDashboardData(): array
    {
        $habitaciones = Habitacion::with([
            'ultimoEstado',
            'ocupacionActiva' => function ($q) {
                $q->with(['consumos.producto', 'pagos', 'clientes', 'promocion.productos', 'tarifa']);
            },
            'reservaActiva',
        ])->orderBy('numero')->get();

        $ocupadas = 0;
        $reservadas = 0;
        $limpieza = 0;
        $disponibles = 0;

        foreach ($habitaciones as $h) {
            match ($h->estado) {
                'Ocupada' => $ocupadas++,
                'Reservada' => $reservadas++,
                'Limpieza' => $limpieza++,
                'Disponible' => $disponibles++,
                default => null,
            };
        }

        return compact('habitaciones', 'ocupadas', 'reservadas', 'limpieza', 'disponibles');
    }

    public function getDatosOcupacion(Ocupacion $ocupacion): array
    {
        $ocupacion->load([
            'clientes',
            'consumos.producto',
            'pagos',
            'observaciones.user',
            'promocion.productos',
            'tarifa',
            'historialEstados',
        ]);

        $totalConsumos = $ocupacion->consumos()->where('origen', 'Consumo')->sum('total');
        $totalPagado = $ocupacion->pagos()->sum('monto');
        $total = $ocupacion->precio_base + $totalConsumos;

        $tieneCortesia = $ocupacion->consumos->contains(function ($consumo) {
            return $consumo->producto && $consumo->producto->cortesia;
        });

        $hoy = now();
        $manana = $hoy->copy()->addDay()->startOfDay();
        $esVispera = Feriado::whereDate('fecha', $manana)->exists();
        $dia = $hoy->dayOfWeek;

        if ($esVispera) {
            $regla = 'Víspera';
        } elseif (in_array($dia, [1, 2, 3, 4])) {
            $regla = 'D-J';
        } elseif ($dia === 5) {
            $regla = 'Viernes';
        } elseif ($dia === 6) {
            $regla = 'Sábado';
        } else {
            $regla = 'D-J';
        }

        $tipoTiempo = $ocupacion->tarifa?->tipo_tiempo ?? '8h';
        $currentTime = $hoy->format('H:i');

        $promocionesAplicables = Promocion::with('productos')
            ->activas()
            ->get()
            ->filter(function ($promocion) use ($regla, $tipoTiempo, $currentTime) {
                if ($promocion->horas_beneficio <= 0) return false;

                $key = $regla . '_' . $tipoTiempo;
                $tarifas = $promocion->tarifas ?? [];
                if (!in_array($key, $tarifas)) return false;

                if ($promocion->desde && $promocion->hasta) {
                    if ($promocion->desde <= $promocion->hasta) {
                        if ($currentTime < $promocion->desde || $currentTime > $promocion->hasta) return false;
                    } else {
                        if ($currentTime < $promocion->desde && $currentTime > $promocion->hasta) return false;
                    }
                }

                return true;
            })
            ->values();

        return [
            'ocupacion' => $ocupacion,
            'total_consumos' => $totalConsumos,
            'total' => $total,
            'saldo' => $total - $totalPagado,
            'regla' => $regla,
            'tipo_tiempo' => $tipoTiempo,
            'promociones_aplicables' => $promocionesAplicables,
            'tiene_cortesia' => $tieneCortesia,
        ];
    }
}
