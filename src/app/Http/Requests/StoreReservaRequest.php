<?php
namespace App\Http\Requests;

use App\Services\RUTService;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rut' => ['required', 'string', 'max:20', function ($attribute, $value, $fail) {
                if (!RUTService::validar($value)) {
                    $fail('El RUT ingresado no es válido.');
                }
            }],
            'nombre' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'personas' => 'required|integer|min:1|max:10',
            'observaciones' => 'nullable|string|max:500',
            'habitacion_id' => 'nullable|exists:habitaciones,id',
        ];
    }

    public function messages(): array
    {
        return [
            'rut.required' => 'El RUT es obligatorio.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.after_or_equal' => 'La fecha debe ser hoy o posterior.',
            'hora.required' => 'La hora es obligatoria.',
            'personas.required' => 'La cantidad de personas es obligatoria.',
            'personas.min' => 'Debe haber al menos 1 persona.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('rut')) {
            $this->merge([
                'rut' => RUTService::limpiar($this->rut),
            ]);
        }
    }
}
