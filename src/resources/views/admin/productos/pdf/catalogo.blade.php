<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page {
    margin: 18mm 12mm 12mm 12mm;
}

body {
    font-family: 'serif';
    color: #1a1a1a;
    font-size: 10pt;
    line-height: 1.4;
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

.header {
    text-align: center;
    padding-bottom: 4mm;
    margin-bottom: 4mm;
    border-bottom: 2px solid #c9a84c;
}

.header h1 {
    color: #111;
    font-size: 18pt;
    margin: 0;
    letter-spacing: 4px;
    text-transform: uppercase;
    font-weight: 400;
}

.header .hotel {
    color: #666;
    font-size: 8pt;
    letter-spacing: 2px;
    margin-top: 2mm;
}

.header .fecha {
    color: #888;
    font-size: 7pt;
    letter-spacing: 1px;
    margin-top: 1mm;
}

.columns {
    width: 100%;
}

.columns td {
    width: 50%;
    vertical-align: top;
    padding-right: 6mm;
}

.columns td:last-child {
    padding-right: 0;
    padding-left: 6mm;
}

.category-title {
    font-size: 11pt;
    color: #c9a84c;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-top: 3mm;
    margin-bottom: 2mm;
    padding-bottom: 0.5mm;
    border-bottom: 1px solid #e0d5b8;
}

.product-row {
    padding: 0.8mm 0;
    border-bottom: 1px dotted #ece5d5;
    overflow: hidden;
}

.product-name {
    font-size: 9pt;
    color: #1a1a1a;
    float: left;
    max-width: 70%;
}

.product-price {
    font-size: 9pt;
    color: #c9a84c;
    font-weight: bold;
    float: right;
}

.footer {
    position: fixed;
    bottom: 8mm;
    left: 12mm;
    right: 12mm;
    text-align: center;
    color: #aaa;
    font-size: 6.5pt;
    border-top: 1px solid #e0d5b8;
    padding-top: 2mm;
}

.pdftable {
    width: 100%;
    border-collapse: collapse;
}
.pdftable td {
    width: 50%;
    vertical-align: top;
    padding: 0;
}
.pdftable .left-col {
    padding-right: 5mm;
}
.pdftable .right-col {
    padding-left: 5mm;
}

.page-break {
    page-break-before: always;
}
</style>
</head>
<body>

<div class="watermark">
    <img src="{{ $iconoPath }}" width="400">
</div>

<div class="header">
    <h1>Catálogo de Productos</h1>
    <div class="hotel">Los Gatitos Hotel</div>
    <div class="fecha">{{ now()->format('d/m/Y') }}</div>
</div>

@php
    $leftCat = 'Alcohol';
    $leftItems = $productos->get($leftCat, collect());
    $rightCats = $productos->except($leftCat);
@endphp

<table class="pdftable">
<tr>
<td class="left-col">
    <div class="category-title">{{ $leftCat }}</div>
    @foreach($leftItems as $producto)
    <div class="product-row">
        <span class="product-name">{{ $producto->nombre }}</span>
        <span class="product-price">${{ number_format($producto->precio, 0, ',', '.') }}</span>
    </div>
    @endforeach
</td>
<td class="right-col">
    @foreach($rightCats as $categoria => $items)
    <div class="category-title">{{ $categoria }}</div>
        @foreach($items as $producto)
        <div class="product-row">
            <span class="product-name">{{ $producto->nombre }}</span>
            <span class="product-price">${{ number_format($producto->precio, 0, ',', '.') }}</span>
        </div>
        @endforeach
    @endforeach
</td>
</tr>
</table>

<div class="footer">
    Los Gatitos Hotel &mdash; Catálogo de Productos &mdash; {{ now()->format('d/m/Y') }}
</div>

</body>
</html>