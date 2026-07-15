<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromocionProducto extends Model
{
    protected $table = 'promocion_producto';

    protected $fillable = [
        'promocion_id',
        'producto_id',
        'cantidad',
    ];

    public function promocion()
    {
        return $this->belongsTo(Promocion::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
