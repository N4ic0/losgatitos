<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observacione extends Model
{
    use HasFactory;

    protected $table = 'observaciones';

    protected $fillable = [
        'ocupacion_id',
        'contenido',
        'user_id',
    ];

    public function ocupacion()
    {
        return $this->belongsTo(Ocupacion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
