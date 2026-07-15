<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingreso extends Model
{
    protected $fillable = [
        'producto_id',
        'cantidad',
        'rut_proveedor',
        'nombre_proveedor',
        'fecha',
        'costo_neto',
        'tipo_documento',
        'numero_documento',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'costo_neto' => 'integer',
            'cantidad' => 'integer',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
