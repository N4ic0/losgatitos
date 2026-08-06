<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page {
    margin: 14mm 10mm 22mm 10mm;
    size: A4 portrait;
}

body {
    font-family: Helvetica, Arial, sans-serif;
    color: #1f2937;
    font-size: 8.5pt;
    line-height: 1.35;
    padding-bottom: 6mm;
}

.header {
    border-bottom: 1.5px solid #b08a3e;
    padding-bottom: 3mm;
    margin-bottom: 5mm;
}
.header-table { width: 100%; border-collapse: collapse; }
.header-table td { vertical-align: middle; }
.header-logo { width: 34mm; text-align: left; }
.header-title { text-align: center; }
.header-title h1 { margin: 0; font-size: 15pt; letter-spacing: 3px; color: #111; text-transform: uppercase; font-weight: bold; }
.header-title .sub { font-size: 7pt; color: #555; letter-spacing: 1.5px; margin-top: 1mm; text-transform: uppercase; }
.header-meta { width: 34mm; text-align: right; font-size: 6.5pt; color: #666; line-height: 1.5; }

.ocp { margin-bottom: 4mm; page-break-inside: avoid; }
.ocp-head { width: 100%; border-collapse: collapse; background: #f3efdf; border-left: 3px solid #b08a3e; }
.ocp-head td { padding: 1.6mm 3mm; font-size: 9pt; font-weight: bold; color: #111; }
.ocp-head .right { text-align: right; font-weight: normal; color: #555; font-size: 7.5pt; }

.meta { width: 100%; border-collapse: collapse; margin-top: 2mm; }
.meta td { padding: 0.8mm 2mm; font-size: 7.5pt; }
.meta .lbl { color: #666; width: 17mm; }

.sec-title {
    font-size: 8pt;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #b08a3e;
    font-weight: bold;
    margin: 3mm 0 1mm 0;
    border-bottom: 0.8px solid #e0d8c8;
    padding-bottom: 0.5mm;
}

table.data { width: 100%; border-collapse: collapse; margin-bottom: 2mm; }
table.data th { background: #e9e2cc; color: #333; font-size: 7pt; text-transform: uppercase; letter-spacing: 0.5px; padding: 1.2mm 2mm; text-align: left; }
table.data td { padding: 1mm 2mm; font-size: 7.5pt; border-bottom: 0.3px solid #e6e1d4; }
table.data tr:last-child td { border-bottom: none; }
.tright { text-align: right; }
.tcenter { text-align: center; }

.ocp-total { width: 100%; border-collapse: collapse; margin-top: 2mm; }
.ocp-total td { padding: 1mm 2mm; font-size: 8pt; }
.ocp-total .lbl { text-align: right; color: #666; }
.ocp-total .val { text-align: right; font-weight: bold; width: 24mm; }
.ocp-total .grand td { border-top: 1.2px solid #b08a3e; font-size: 9.5pt; font-weight: bold; }

.divider { border-top: 1px dashed #d4c9a8; margin: 4mm 0; }

.footer-summary { page-break-before: always; margin-top: 8mm; page-break-inside: avoid; }
.sum-head { text-align: center; font-size: 13pt; font-weight: bold; letter-spacing: 3px; text-transform: uppercase; color: #111; margin-bottom: 5mm; }
.sum-table { width: 100%; border-collapse: collapse; margin-bottom: 6mm; }
.sum-table th { background: #e9e2cc; color: #333; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.5px; padding: 1.8mm 3mm; text-align: left; }
.sum-table td { padding: 1.8mm 3mm; font-size: 9pt; border-bottom: 0.3px solid #e6e1d4; }
.sum-table .tright { text-align: right; }
.sum-table .grand td { border-top: 1.5px solid #b08a3e; font-size: 11pt; font-weight: bold; background: #faf7ec; }

.signature { width: 100%; border-collapse: collapse; margin-top: 18mm; }
.signature td { text-align: center; }
.signature .line { border-bottom: 1px solid #333; width: 80mm; margin: 0 auto; }
.signature .label { font-size: 8pt; color: #555; margin-top: 1.5mm; }
.signature .title { font-size: 9pt; font-weight: bold; margin-top: 1mm; }

.footer {
    position: fixed;
    bottom: 8mm;
    left: 10mm;
    right: 10mm;
    text-align: center;
    color: #aaa;
    font-size: 6pt;
    border-top: 1px solid #e0d5b8;
    padding-top: 1.5mm;
}
</style>
</head>
<body>

@php
    $formaLabels = ['efectivo' => 'Efectivo', 'transferencia' => 'Transferencia', 'tarjeta' => 'Tarjeta'];
    function fmt($n) { return '$' . number_format((float) $n, 0, ',', '.'); }
@endphp

<div class="header">
    <table class="header-table">
        <tr>
            <td class="header-logo"><img src="{{ $logoPath }}" width="75" alt="Los Gatitos"></td>
            <td class="header-title">
                <h1>Informe de Cierre</h1>
                <div class="sub">Los Gatitos Hotel</div>
            </td>
            <td class="header-meta">
                Desde: {{ $desde->format('d/m/Y H:i') }}<br>
                Hasta: {{ $hasta->format('d/m/Y H:i') }}<br>
                Generado: {{ now()->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>
</div>

@forelse($ocupaciones as $o)
    @php
        $fin = $o->fecha_fin ?: now();
        $dur = $o->fecha_inicio->diff($fin);
        $duracion = $dur->h . 'h ' . $dur->i . 'm';
        $estado = $o->fecha_fin === null ? 'EN CURSO' : 'FINALIZADA';
        $sumConsumos = $o->consumos->sum('total');
        $sumPagos = $o->pagos->sum('monto');
        $totalOc = $o->precio_base + $sumConsumos + $o->propinas;
        $saldoOc = $totalOc - $sumPagos;
    @endphp

    <div class="ocp">
        <table class="ocp-head">
            <tr>
                <td>Ocupación #{{ $o->id }} &mdash; Hab. {{ $o->habitacion?->numero ?? '-' }} ({{ $o->habitacion?->categoria ?? '-' }})</td>
                <td class="right">{{ $estado }}</td>
            </tr>
        </table>

        <table class="meta">
            <tr>
                <td class="lbl">Inicio</td>
                <td>{{ $o->fecha_inicio->format('d/m/Y H:i') }}</td>
                <td class="lbl">Tarifa</td>
                <td>{{ $o->tarifa?->tipo_tiempo ?? '-' }}</td>
                <td class="lbl">Vehículo</td>
                <td>{{ $o->vehiculo ? 'Vehículo' : 'Peatón' }}</td>
            </tr>
            <tr>
                <td class="lbl">Fin</td>
                <td>{{ $o->fecha_fin?->format('d/m/Y H:i') ?? 'En curso' }}</td>
                <td class="lbl">Promoción</td>
                <td>{{ $o->promocion?->titulo ?? '—' }}</td>
                <td class="lbl">Patente</td>
                <td>{{ $o->vehiculo ? ($o->patente ?: '—') : '—' }}</td>
            </tr>
            <tr>
                <td class="lbl">Duración</td>
                <td>{{ $duracion }}</td>
                <td class="lbl">Personas extra</td>
                <td>{{ $o->personas_adicionales }}</td>
                <td class="lbl">Horas beneficio</td>
                <td>{{ $o->horas_beneficio }}h</td>
            </tr>
        </table>

        @if($o->clientes->count())
        <div class="sec-title">Clientes ({{ $o->clientes->count() }})</div>
        <table class="data">
            <tr><th>Tipo Doc.</th><th>N&deg; Documento</th><th>Nombre</th><th>Apellidos</th><th>Nacionalidad</th></tr>
            @foreach($o->clientes as $c)
            <tr>
                <td>{{ $c->tipo_documento ?? '-' }}</td>
                <td>{{ $c->numero_documento ?? '-' }}</td>
                <td>{{ $c->nombres ?? '-' }}</td>
                <td>{{ $c->apellidos ?? '-' }}</td>
                <td>{{ $c->nacionalidad ?? '-' }}</td>
            </tr>
            @endforeach
        </table>
        @endif

        @if($o->consumos->count())
        <div class="sec-title">Consumos ({{ $o->consumos->count() }})</div>
        <table class="data">
            <tr><th>Producto</th><th class="tcenter">Cant.</th><th class="tright">P. Unit.</th><th class="tright">Total</th><th>Fecha</th></tr>
            @foreach($o->consumos as $c)
            <tr>
                <td>{{ $c->producto?->nombre ?? '-' }}@if($c->total == 0) <i>(cortes&iacute;a)</i>@endif</td>
                <td class="tcenter">{{ $c->cantidad }}</td>
                <td class="tright">{{ fmt($c->precio_unitario) }}</td>
                <td class="tright">{{ fmt($c->total) }}</td>
                <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </table>
        @endif

        @if($o->pagos->count())
        <div class="sec-title">Pagos ({{ $o->pagos->count() }})</div>
        <table class="data">
            <tr><th>Fecha</th><th>Forma de Pago</th><th class="tright">Monto</th></tr>
            @foreach($o->pagos as $p)
            <tr>
                <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $formaLabels[$p->forma_pago] ?? ucfirst($p->forma_pago) }}</td>
                <td class="tright">{{ fmt($p->monto) }}</td>
            </tr>
            @endforeach
        </table>
        @endif

        <table class="ocp-total">
            <tr><td class="lbl">Precio base</td><td class="val">{{ fmt($o->precio_base) }}</td></tr>
            <tr><td class="lbl">Consumos</td><td class="val">{{ fmt($sumConsumos) }}</td></tr>
            @if($o->propinas > 0)
            <tr><td class="lbl">Propina</td><td class="val">{{ fmt($o->propinas) }}</td></tr>
            @endif
            <tr class="grand"><td class="lbl">Total ocupaci&oacute;n</td><td class="val">{{ fmt($totalOc) }}</td></tr>
            <tr><td class="lbl">Pagado</td><td class="val">{{ fmt($sumPagos) }}</td></tr>
            <tr>
                <td class="lbl">Saldo</td>
                <td class="val" style="color: {{ $saldoOc > 0 ? '#b91c1c' : '#15803d' }};">{{ fmt($saldoOc) }}</td>
            </tr>
        </table>
    </div>

    @if(!$loop->last)
    <div class="divider"></div>
    @endif
@empty
    <p style="text-align:center; color:#666; margin-top:15mm;">No hay ocupaciones en el per&iacute;odo seleccionado.</p>
@endforelse

<!-- -->

<div class="footer-summary">
    <div class="sum-head">Resumen del Per&iacute;odo</div>

    <table class="sum-table">
        <tr><th>Resumen de ocupaciones</th><th class="tright">Cant.</th></tr>
        <tr><td>Ocupaciones completadas</td><td class="tright">{{ $completadas }}</td></tr>
        <tr><td>Ocupaciones en curso</td><td class="tright">{{ $enCurso }}</td></tr>
    </table>

    <table class="sum-table">
        <tr><th>Dinero del per&iacute;odo</th><th class="tright">Monto</th></tr>
        <tr><td>Total ocupaci&oacute;n del per&iacute;odo (precio base + consumos + propina)</td><td class="tright">{{ fmt($totalOcupaciones) }}</td></tr>
        <tr><td>&mdash; Total consumos</td><td class="tright">{{ fmt($totalConsumos) }}</td></tr>
        <tr><td>&mdash; Total propinas</td><td class="tright">{{ fmt($totalPropinas) }}</td></tr>
        <tr><td style="height: 2mm;"></td><td></td></tr>
        @foreach($totalesPorForma as $forma => $monto)
        <tr><td>Total {{ $formaLabels[$forma] ?? ucfirst($forma) }}</td><td class="tright">{{ fmt($monto) }}</td></tr>
        @endforeach
        <tr class="grand"><td>DINERO COBRADO EN EL PER&Iacute;ODO</td><td class="tright">{{ fmt($totalPagos + $totalPropinas) }}</td></tr>
    </table>

    <table class="signature">
        <tr>
            <td>
                <div class="line"></div>
                <div class="label">Firma Responsable</div>
                <div class="title">Los Gatitos Hotel</div>
            </td>
        </tr>
    </table>
</div>

<div class="footer">
    Los Gatitos Hotel &mdash; Informe de Cierre &mdash; {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>
