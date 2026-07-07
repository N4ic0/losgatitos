<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'categoria',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'precio' => 'integer',
        ];
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeColaciones($query)
    {
        return $query->where('categoria', 'Colacion');
    }

    public function scopeProductos($query)
    {
        return $query->where('categoria', 'Producto');
    }
}
