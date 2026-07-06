<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTarifaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->role === 'administrador';
    }

    public function rules(): array
    {
        return [
            'categoria' => 'required|in:Suite,Departamento',
            'tipo_tiempo' => 'required|in:3h,8h,Hora adicional',
            'precio_dj' => 'required|integer|min:0',
            'precio_viernes' => 'required|integer|min:0',
            'precio_sabado' => 'required|integer|min:0',
            'precio_vispera' => 'nullable|integer|min:0',
            'activo' => 'boolean',
        ];
    }
}
