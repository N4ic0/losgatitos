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
    color: #1a1a1a;
    font-size: 10pt;
    line-height: 1.5;
}

.watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    margin-left: -200px;
    margin-top: -200px;
    opacity: 0.06;
    z-index: -1;
}

.cover {
    text-align: center;
    padding-top: 100mm;
    page-break-after: always;
}

.cover h1 {
    color: #111;
    font-size: 26pt;
    margin: 0;
    letter-spacing: 6px;
    text-transform: uppercase;
    font-weight: 400;
}

.cover .subtitle {
    color: #666;
    font-size: 9pt;
    letter-spacing: 3px;
    margin-top: 4mm;
}

.cover .line {
    width: 60mm;
    border-top: 1px solid #c9a84c;
    margin: 6mm auto 0 auto;
}

.content-header {
    text-align: center;
    padding-bottom: 6mm;
    margin-bottom: 5mm;
    border-bottom: 2px solid #c9a84c;
}

.content-header h2 {
    color: #111;
    font-size: 16pt;
    margin: 0;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 400;
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
    overflow: hidden;
}

.product-item {
    float: left;
    width: 48%;
    padding: 1.5mm 0;
    border-bottom: 1px dotted #e0d5b8;
}

.product-item:nth-child(odd) {
    clear: left;
    margin-right: 4%;
}

.product-item .inner {
    display: block;
    overflow: hidden;
}

.product-name {
    font-size: 10pt;
    color: #1a1a1a;
    float: left;
}

.product-price {
    font-size: 10pt;
    color: #c9a84c;
    font-weight: bold;
    float: right;
}

.footer {
    position: fixed;
    bottom: 10mm;
    left: 15mm;
    right: 15mm;
    text-align: center;
    color: #999;
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

<div class="cover">
    <h1>Catálogo de Productos</h1>
    <div class="subtitle">Los Gatitos Hotel</div>
    <div class="subtitle">{{ now()->format('d/m/Y') }}</div>
    <div class="line"></div>
</div>

<div class="content-header">
    <h2>Nuestros Productos</h2>
</div>

@foreach($productos as $categoria => $items)
<div class="category-section">
    <div class="category-title">{{ $categoria }}</div>
    <div class="products-grid">
        @foreach($items as $producto)
        <div class="product-item">
            <div class="inner">
                <span class="product-name">{{ $producto->nombre }}</span>
                <span class="product-price">${{ number_format($producto->precio, 0, ',', '.') }}</span>
            </div>
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