<?php
namespace App\Services;

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

    public function iniciarOcupacion(Habitacion $habitacion, ?int $promocionId = null): Ocupacion
    {
        $this->cerrarEstadoActual($habitacion);

        $tarifa = Tarifa::where('categoria', $habitacion->categoria)
            ->where('tipo_tiempo', '8h')
            ->where('activo', true)
            ->first();

        $precioBase = 0;
        $horasBeneficio = 0;

        if ($tarifa) {
            $precios = $this->tarifaService->calcularPrecio($habitacion->categoria, now()->format('Y-m-d'), '8h');
            $precioBase = $precios['precio_base'];
        }

        $ocupacion = Ocupacion::create([
            'habitacion_id' => $habitacion->id,
            'tarifa_id' => $tarifa?->id,
            'precio_base' => $precioBase,
            'fecha_inicio' => now(),
            'promocion_id' => $promocionId,
            'horas_beneficio' => $horasBeneficio,
        ]);

        if ($promocionId) {
            $promocion = Promocion::with('productos')->find($promocionId);
            if ($promocion) {
                foreach ($promocion->productos as $producto) {
                    Consumo::create([
                        'ocupacion_id' => $ocupacion->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $producto->pivot->cantidad,
                        'precio_unitario' => 0,
                        'total' => 0,
                        'origen' => 'Promocion',
                        'user_id' => auth()->id(),
                    ]);
                }

                $reglas = $promocion->reglas;
                if ($reglas && isset($reglas['horas_beneficio'])) {
                    $ocupacion->update(['horas_beneficio' => $reglas['horas_beneficio']]);
                }
            }
        }

        $this->cambiarEstado($habitacion, 'Ocupada', $ocupacion->id);

        $this->auditoriaService->registrar('iniciar_ocupacion', 'ocupaciones', $ocupacion->id, null, $ocupacion->toArray());

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
                $q->with(['consumos.producto', 'pagos', 'clientes', 'promocion.productos']);
            },
            'reservaActiva',
        ])->orderBy('numero')->get();

        $ocupadas = 0;
        $disponibles = 0;
        $reservadas = 0;
        $limpieza = 0;

        foreach ($habitaciones as $h) {
            match ($h->estado) {
                'Ocupada' => $ocupadas++,
                'Disponible' => $disponibles++,
                'Reservada' => $reservadas++,
                'Limpieza' => $limpieza++,
                default => null,
            };
        }

        return compact('habitaciones', 'ocupadas', 'disponibles', 'reservadas', 'limpieza');
    }

    public function getDatosOcupacion(Ocupacion $ocupacion): array
    {
        $ocupacion->load([
            'clientes',
            'consumos.producto',
            'pagos',
            'observaciones.user',
            'promocion',
            'tarifa',
            'historialEstados',
        ]);

        $totalConsumos = $ocupacion->consumos()->where('origen', 'Consumo')->sum('total');
        $totalPagado = $ocupacion->pagos()->sum('monto');
        $total = $ocupacion->precio_base + $totalConsumos;

        return [
            'ocupacion' => $ocupacion,
            'total_consumos' => $totalConsumos,
            'total' => $total,
            'saldo' => $total - $totalPagado,
        ];
    }
}
