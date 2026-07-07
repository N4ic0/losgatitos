<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumo extends Model
{
    use HasFactory;

    protected $fillable = [
        'ocupacion_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'total',
        'origen',
        'observacion',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'precio_unitario' => 'integer',
            'total' => 'integer',
        ];
    }

    public function ocupacion()
    {
        return $this->belongsTo(Ocupacion::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
