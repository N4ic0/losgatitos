<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditorium extends Model
{
    use HasFactory;

    protected $table = 'auditoria';

    protected $fillable = [
        'user_id',
        'accion',
        'tabla',
        'registro_id',
        'datos_antiguos',
        'datos_nuevos',
        'ip',
    ];

    protected function casts(): array
    {
        return [
            'datos_antiguos' => 'json',
            'datos_nuevos' => 'json',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
