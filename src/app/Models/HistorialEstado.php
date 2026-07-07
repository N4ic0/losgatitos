<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialEstado extends Model
{
    use HasFactory;

    protected $table = 'historial_estados';

    protected $fillable = [
        'habitacion_id',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'user_id',
        'ocupacion_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
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

    public function ocupacion()
    {
        return $this->belongsTo(Ocupacion::class);
    }

    public function scopeActivo($query)
    {
        return $query->whereNull('fecha_fin');
    }
}
