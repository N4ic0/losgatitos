<?php
namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        Producto::create(['nombre' => 'Té o Café', 'descripcion' => 'Té o café tradicional', 'precio' => 0, 'factor' => 'unidad', 'stock_actual' => 0, 'stock_minimo' => 0, 'stock_maximo' => 0, 'categoria' => 'Colacion', 'activo' => true]);
        Producto::create(['nombre' => 'Tostadas', 'descripcion' => 'Tostadas de pan', 'precio' => 0, 'factor' => 'unidad', 'stock_actual' => 0, 'stock_minimo' => 0, 'stock_maximo' => 0, 'categoria' => 'Colacion', 'activo' => true]);
        Producto::create(['nombre' => 'Mantequilla', 'descripcion' => 'Porción de mantequilla', 'precio' => 0, 'factor' => 'unidad', 'stock_actual' => 0, 'stock_minimo' => 0, 'stock_maximo' => 0, 'categoria' => 'Colacion', 'activo' => true]);
        Producto::create(['nombre' => 'Mermelada', 'descripcion' => 'Porción de mermelada', 'precio' => 0, 'factor' => 'unidad', 'stock_actual' => 0, 'stock_minimo' => 0, 'stock_maximo' => 0, 'categoria' => 'Colacion', 'activo' => true]);
    }
}
