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
}
