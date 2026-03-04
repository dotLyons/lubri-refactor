<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #ddd; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #4F46E5; }
        .info-container { display: table; width: 100%; margin-bottom: 20px; }
        .info-section { display: table-cell; width: 50%; padding-right: 20px; }
        .info-section strong { display: block; margin-bottom: 5px; color: #666; font-size: 12px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border-bottom: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f9fafb; color: #555; text-transform: uppercase; font-size: 12px; }
        .totals { width: 100%; display: table; margin-top: 20px; }
        .totals-column { display: table-cell; width: 50%; text-align: right; }
        .total-row { clear: both; padding: 5px 0; }
        .total-row strong { margin-right: 20px; }
        .status-paid { color: #10B981; font-weight: bold; }
        .status-partial { color: #F59E0B; font-weight: bold; }
        .status-unpaid { color: #EF4444; font-weight: bold; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; background: #eee; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Centro de Servicios Multipolar</h1>
        <p>Factura #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}<br>Fecha: {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
        <p>
            @if($invoice->status->value === 'paid') <span class="badge" style="color:#fff;background:#10B981;">PAGADA</span>
            @elseif($invoice->status->value === 'partial') <span class="badge" style="color:#fff;background:#F59E0B;">PARCIAL</span>
            @else <span class="badge" style="color:#fff;background:#EF4444;">PENDIENTE</span>
            @endif
        </p>
    </div>

    <div class="info-container">
        <div class="info-section">
            <strong>Cliente</strong>
            Nombre: {{ $invoice->customer->first_name }} {{ $invoice->customer->last_name }}<br>
            DNI/CUIT: {{ $invoice->customer->dni }}<br>
            Tel: {{ $invoice->customer->phone ?? 'N/A' }}
        </div>
        <div class="info-section">
            <strong>Orden de Trabajo</strong>
            Ticket: #{{ str_pad($invoice->work_order_id, 5, '0', STR_PAD_LEFT) }}<br>
            Vehículo: {{ $invoice->workOrder->vehicle?->brand ?? 'S/R' }} {{ $invoice->workOrder->vehicle?->model ?? '' }}<br>
            Patente: {{ $invoice->workOrder->vehicle?->license_plate ?? 'S/R' }}
        </div>
    </div>

    <strong>Detalle de Consumos</strong>
    <table>
        <thead>
            <tr>
                <th>Producto/Servicio</th>
                <th>Cant.</th>
                <th>Precio Un.</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->workOrder->items as $item)
                <tr>
                    <td>{{ $item->product?->product_name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="page-break-inside: avoid;">
        <strong>Historial de Pagos y Recargos Múltiples</strong>
        @if($invoice->payments->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Método</th>
                        <th>Detalle</th>
                        <th style="text-align: right;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                        <tr>
                            <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $payment->payment_method->label() }}</td>
                            <td>
                                @if($payment->cardPlan)
                                    {{ $payment->cardPlan->card->name }} ({{ $payment->cardPlan->name }})
                                @endif
                            </td>
                            <td style="text-align: right;">${{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color:#666; font-size:12px;">No se registran pagos.</p>
        @endif
    </div>

    <div class="totals">
        <div style="display:table-cell; width: 50%;"></div>
        <div class="totals-column">
            <div class="total-row"><strong>Total Abonado:</strong> <span style="color:#10B981;">${{ number_format($invoice->paid_amount, 2) }}</span></div>
            <div class="total-row" style="font-size: 18px; margin-top: 10px;"><strong>Saldo Pendiente:</strong> <span style="color:#EF4444;">${{ number_format($invoice->balance_due, 2) }}</span></div>
        </div>
    </div>

</body>
</html>
