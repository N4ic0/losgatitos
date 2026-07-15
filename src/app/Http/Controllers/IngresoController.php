<?php
namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Producto;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'rut_proveedor' => 'required|string|max:20',
            'nombre_proveedor' => 'required|string|max:255',
            'fecha' => 'required|date',
            'costo_neto' => 'required|integer|min:0',
            'tipo_documento' => 'required|in:Factura,Boleta',
            'numero_documento' => 'nullable|string|max:50',
        ]);

        $data['user_id'] = auth()->id();

        $ingreso = Ingreso::create($data);

        $producto = Producto::findOrFail($data['producto_id']);
        $producto->increment('stock_actual', $data['cantidad']);

        $this->auditoriaService->registrar('ingreso_stock', 'ingresos', $ingreso->id, null, $ingreso->toArray());

        return redirect()->route('admin.productos.index')->with('success', 'Ingreso registrado. Stock actualizado a ' . $producto->stock_actual . '.');
    }
}
