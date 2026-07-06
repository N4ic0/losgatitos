<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'rut',
        'nombre',
        'email',
        'telefono',
        'fecha',
        'hora',
        'personas',
        'observaciones',
        'estado',
        'habitacion_id',
        'user_id',
        'hora_ingreso',
        'hora_salida',
        'horas_adicionales',
        'tercera_persona',
        'precio_base',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'hora' => 'datetime:H:i',
            'hora_ingreso' => 'datetime',
            'hora_salida' => 'datetime',
            'tercera_persona' => 'boolean',
            'personas' => 'integer',
            'horas_adicionales' => 'integer',
            'precio_base' => 'integer',
            'total' => 'integer',
        ];
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
