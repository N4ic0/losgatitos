<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'ocupacion_id',
        'monto',
        'forma_pago',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'integer',
        ];
    }

    public function ocupacion()
    {
        return $this->belongsTo(Ocupacion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
