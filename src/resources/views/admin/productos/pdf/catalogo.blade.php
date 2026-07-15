<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page {
    margin: 20mm 15mm 15mm 15mm;
}

body {
    font-family: 'serif';
    color: #2d2d2d;
    font-size: 10pt;
    line-height: 1.5;
}

.watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0.06;
    z-index: -1;
}

.header {
    text-align: center;
    padding-bottom: 8mm;
    margin-bottom: 5mm;
    border-bottom: 2px solid #c9a84c;
}

.header h1 {
    color: #2d2d2d;
    font-size: 22pt;
    margin: 0;
    letter-spacing: 4px;
    text-transform: uppercase;
    font-weight: 400;
}

.header .subtitle {
    color: #8a8a8a;
    font-size: 8pt;
    letter-spacing: 2px;
    margin-top: 2mm;
}

.category-section {
    margin-bottom: 6mm;
    page-break-inside: avoid;
}

.category-title {
    font-size: 12pt;
    color: #c9a84c;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 3mm;
    padding-bottom: 1mm;
    border-bottom: 1px solid #e0d5b8;
}

.products-grid {
    display: flex;
    flex-wrap: wrap;
}

.product-item {
    width: 48%;
    padding: 1.5mm 2mm;
    border-bottom: 1px dotted #e8e0d0;
    display: flex;
    justify-content: space-between;
    align-items: baseline;
}

.product-item:nth-child(odd) {
    margin-right: 4%;
}

.product-name {
    font-size: 10pt;
    color: #3a3a3a;
}

.product-price {
    font-size: 10pt;
    color: #c9a84c;
    font-weight: bold;
    white-space: nowrap;
}

.footer {
    position: fixed;
    bottom: 10mm;
    left: 15mm;
    right: 15mm;
    text-align: center;
    color: #bbb;
    font-size: 7pt;
    border-top: 1px solid #e0d5b8;
    padding-top: 2mm;
}
</style>
</head>
<body>

<div class="watermark">
    <img src="{{ $iconoPath }}" width="400">
</div>

<div class="header">
    <h1>Catálogo de Productos</h1>
    <div class="subtitle">Los Gatitos Hotel • {{ now()->format('d/m/Y') }}</div>
</div>

@foreach($productos as $categoria => $items)
<div class="category-section">
    <div class="category-title">{{ $categoria }}</div>
    <div class="products-grid">
        @foreach($items as $producto)
        <div class="product-item">
            <span class="product-name">{{ $producto->nombre }}</span>
            <span class="product-price">${{ number_format($producto->precio, 0, ',', '.') }}</span>
        </div>
        @endforeach
    </div>
</div>
@endforeach

<div class="footer">
    Los Gatitos Hotel &mdash; Catálogo de Productos &mdash; {{ now()->format('d/m/Y') }}
</div>

</body>
</html>