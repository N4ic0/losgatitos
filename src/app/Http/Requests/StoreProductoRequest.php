<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasPermission('productos.create') || auth()->user()?->hasPermission('productos.edit');
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|integer|min:0',
            'factor' => 'nullable|string|in:cc,kgs,unidad',
            'stock_actual' => 'nullable|numeric|min:0',
            'stock_minimo' => 'nullable|numeric|min:0',
            'stock_maximo' => 'nullable|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categoria' => 'required|string|max:100',
            'activo' => 'boolean',
            'cortesia' => 'boolean',
        ];
    }
}
