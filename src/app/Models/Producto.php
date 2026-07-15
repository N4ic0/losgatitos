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
        'factor',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
        'imagen',
        'categoria',
        'activo',
        'cortesia',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'cortesia' => 'boolean',
            'precio' => 'integer',
            'stock_actual' => 'decimal:3',
            'stock_minimo' => 'decimal:3',
            'stock_maximo' => 'decimal:3',
        ];
    }

    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
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
