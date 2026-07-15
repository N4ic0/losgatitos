<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page {
    margin: 10mm 8mm 8mm 8mm;
}

body {
    font-family: 'DejaVu Serif', serif;
    color: #1a1a1a;
    font-size: 9pt;
    line-height: 1.3;
}

.watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    margin-left: -175px;
    margin-top: -175px;
    opacity: 0.06;
    z-index: -1;
}

.header {
    text-align: center;
    padding-bottom: 2mm;
    margin-bottom: 3mm;
    border-bottom: 1.5px solid #c9a84c;
}

.header h1 {
    color: #111;
    font-size: 16pt;
    margin: 0;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 400;
}

.header .hotel {
    color: #111;
    font-size: 7.5pt;
    letter-spacing: 1.5px;
    margin-top: 1.5mm;
}

.header .fecha {
    color: #555;
    font-size: 6.5pt;
    letter-spacing: 1px;
    margin-top: 0.5mm;
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
    font-size: 10pt;
    color: #111;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-top: 2mm;
    margin-bottom: 1mm;
    padding-bottom: 0.3mm;
    border-bottom: 0.8px solid #c9a84c;
}

.prodtable {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0.5mm;
}
.prodtable tr {
    border-bottom: 0.3px solid #e0d8c8;
}
.prodname {
    font-size: 8.5pt;
    color: #111;
    padding: 0.3mm 0;
    text-align: left;
}
.prodprice {
    font-size: 8.5pt;
    color: #c9a84c;
    font-weight: bold;
    padding: 0.3mm 0 0.3mm 2mm;
    text-align: right;
    white-space: nowrap;
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
    padding-right: 4mm;
}
.pdftable .right-col {
    padding-left: 4mm;
}

.page-break {
    page-break-before: always;
}
</style>
</head>
<body>

<div class="watermark">
    <img src="{{ $iconoPath }}" width="350">
</div>

<div class="header">
    <h1>Catálogo de Productos</h1>
    <div class="hotel">Los Gatitos Hotel</div>
    <div class="fecha">{{ now()->format('d/m/Y') }}</div>
</div>

@php
    $total = $productos->sum(fn($i) => $i->count());
    $half = intval(ceil($total / 2));
    $leftCats = collect();
    $rightCats = collect();
    $acc = 0;
    foreach ($productos as $cat => $items) {
        $cnt = $items->count();
        if ($acc < $half) {
            $leftCats[$cat] = $items;
        } else {
            $rightCats[$cat] = $items;
        }
        $acc += $cnt;
    }
@endphp

<table class="pdftable">
<tr>
<td class="left-col">
    @foreach($leftCats as $categoria => $items)
    <div class="category-title">{{ $categoria }}</div>
    <table class="prodtable">
        @foreach($items as $producto)
        <tr>
            <td class="prodname">{{ $producto->nombre }}</td>
            <td class="prodprice">${{ number_format($producto->precio, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>
    @endforeach
</td>
<td class="right-col">
    @foreach($rightCats as $categoria => $items)
    <div class="category-title">{{ $categoria }}</div>
    <table class="prodtable">
        @foreach($items as $producto)
        <tr>
            <td class="prodname">{{ $producto->nombre }}</td>
            <td class="prodprice">${{ number_format($producto->precio, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>
    @endforeach
</td>
</tr>
</table>

<div class="footer">
    Los Gatitos Hotel &mdash; Catálogo de Productos &mdash; {{ now()->format('d/m/Y') }}
</div>

</body>
</html>