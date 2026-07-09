<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHabitacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasPermission('habitaciones.create') || auth()->user()?->hasPermission('habitaciones.edit');
    }

    public function rules(): array
    {
        return [
            'numero' => [
                'required', 'string', 'max:10',
                Rule::unique('habitaciones', 'numero')->ignore($this->route('habitacion')),
            ],
            'categoria' => 'required|in:Suite,Departamento',
            'estado' => 'required|in:Disponible,Reservada,Ocupada,Limpieza,Mantenimiento',
            'observaciones' => 'nullable|string|max:500',
        ];
    }
}
