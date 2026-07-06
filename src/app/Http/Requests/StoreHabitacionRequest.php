<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->role === 'administrador';
    }

    public function rules(): array
    {
        return [
            'numero' => 'required|string|max:10|unique:habitaciones,numero,' . $this->route('habitacion'),
            'categoria' => 'required|in:Suite,Departamento',
            'estado' => 'required|in:Disponible,Reservada,Ocupada,Limpieza,Mantenimiento',
            'observaciones' => 'nullable|string|max:500',
        ];
    }
}
