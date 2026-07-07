<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    use HasFactory;

    protected $table = 'habitaciones';

    protected $fillable = [
        'numero',
        'categoria',
        'estado',
        'observaciones',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function reservaActiva()
    {
        return $this->hasOne(Reserva::class)->whereIn('estado', ['Reservada', 'Ingresada']);
    }

    public function historialEstados()
    {
        return $this->hasMany(HistorialEstado::class);
    }

    public function ultimoEstado()
    {
        return $this->hasOne(HistorialEstado::class)->whereNull('fecha_fin')->latestOfMany();
    }

    public function ocupaciones()
    {
        return $this->hasMany(Ocupacion::class);
    }

    public function ocupacionActiva()
    {
        return $this->hasOne(Ocupacion::class)->whereNull('fecha_fin')->latestOfMany();
    }
}
