<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasPermission('usuarios.create') || auth()->user()?->hasPermission('usuarios.edit');
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => $userId ? 'nullable|string|min:8' : 'required|string|min:8',
            'rut' => [
                'nullable', 'string', 'max:20',
                Rule::unique('users', 'rut')->ignore($userId),
            ],
            'telefono' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
        ];
    }
}
