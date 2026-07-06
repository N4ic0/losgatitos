<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'fecha_inicio',
        'fecha_fin',
        'activo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now());
    }

    public function scopeVigentes($query)
    {
        return $query->where('fecha_fin', '>=', now());
    }
}
