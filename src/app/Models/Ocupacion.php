<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ocupacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ocupaciones';

    protected $fillable = [
        'habitacion_id',
        'tarifa_id',
        'precio_base',
        'personas_adicionales',
        'fecha_inicio',
        'fecha_fin',
        'promocion_id',
        'horas_beneficio',
        'vehiculo',
        'patente',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
            'precio_base' => 'integer',
            'personas_adicionales' => 'integer',
            'horas_beneficio' => 'integer',
            'vehiculo' => 'boolean',
        ];
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class);
    }

    public function promocion()
    {
        return $this->belongsTo(Promocion::class);
    }

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'ocupacion_cliente');
    }

    public function consumos()
    {
        return $this->hasMany(Consumo::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function observaciones()
    {
        return $this->hasMany(Observacione::class);
    }

    public function historialEstados()
    {
        return $this->hasMany(HistorialEstado::class);
    }

    public function getTotalConsumosAttribute()
    {
        return $this->consumos()->sum('total');
    }

    public function getTotalPagadoAttribute()
    {
        return $this->pagos()->sum('monto');
    }

    public function getTotalAttribute()
    {
        return $this->precio_base + $this->total_consumos;
    }

    public function getSaldoAttribute()
    {
        return $this->total - $this->total_pagado;
    }

    public function scopeActiva($query)
    {
        return $query->whereNull('fecha_fin');
    }
}
