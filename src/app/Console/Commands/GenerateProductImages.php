<?php
namespace App\Console\Commands;

use App\Models\Producto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateProductImages extends Command
{
    protected $signature = 'productos:generate-images';
    protected $description = 'Generate SVG placeholder images for products without an image';

    public function handle()
    {
        $productos = Producto::all();

        $disk = Storage::disk('public');

        foreach ($productos as $producto) {
            $categoryColors = [
                'Alcohol'   => '#8B0000',
                'Baño'      => '#00695C',
                'Bebidas'   => '#1565C0',
                'Colacion'  => '#BF6A0A',
                'Comida'    => '#D84315',
                'Otros'     => '#4A4A7A',
                'Postres'   => '#AD1457',
                'Sandwich'  => '#E65100',
                'Snack'     => '#2E7D32',
            ];
            $bg = $categoryColors[$producto->categoria] ?? '#4A4A4A';

            $nombre = htmlspecialchars($producto->nombre, ENT_QUOTES);
            $cat = htmlspecialchars($producto->categoria, ENT_QUOTES);
            $precio = number_format($producto->precio, 0, ',', '.');

            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 400 400">
  <rect width="400" height="400" fill="{$bg}" rx="16"/>
  <circle cx="200" cy="150" r="55" fill="rgba(255,255,255,0.15)"/>
  <text x="200" y="110" text-anchor="middle" fill="rgba(255,255,255,0.4)" font-size="60" font-family="sans-serif">🍽</text>
  <text x="200" y="250" text-anchor="middle" fill="#fff" font-size="22" font-weight="bold" font-family="sans-serif">{$nombre}</text>
  <rect x="140" y="270" width="120" height="2" rx="1" fill="rgba(255,255,255,0.3)"/>
  <text x="200" y="300" text-anchor="middle" fill="rgba(255,255,255,0.7)" font-size="14" font-family="sans-serif">{$cat}</text>
  <text x="200" y="340" text-anchor="middle" fill="#D4AF37" font-size="20" font-weight="bold" font-family="sans-serif">\${$precio}</text>
</svg>
SVG;

            $filename = "productos/{$producto->id}.svg";
            $disk->put($filename, $svg);
            $producto->update(['imagen' => $filename]);

            $this->info("Generated: {$producto->nombre}");
        }

        $this->info("Done. {$productos->count()} images generated.");
    }
}
