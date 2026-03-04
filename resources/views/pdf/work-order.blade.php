<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Trabajo #{{ str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) }}</title>
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
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; background: #eee; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Centro de Servicios Multipolar</h1>
        <p>Orden de Trabajo #{{ str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) }}<br>Apertura del Turno: {{ $workOrder->scheduled_at ? $workOrder->scheduled_at->format('d/m/Y H:i') : $workOrder->created_at->format('d/m/Y H:i') }}</p>
        <p>
            @if($workOrder->status->value === 'closed') <span class="badge" style="color:#fff;background:#10B981;">CERRADA</span>
            @elseif($workOrder->status->value === 'in_progress') <span class="badge" style="color:#fff;background:#F59E0B;">EN PROGRESO</span>
            @else <span class="badge" style="color:#fff;background:#6366F1;">PENDIENTE</span>
            @endif
        </p>
    </div>

    <div class="info-container">
        <div class="info-section">
            <strong>Cliente</strong>
            Nombre: {{ $workOrder->customer->first_name }} {{ $workOrder->customer->last_name }}<br>
            DNI/CUIT: {{ $workOrder->customer->dni }}<br>
            Tel: {{ $workOrder->customer->phone ?? 'N/A' }}
        </div>
        <div class="info-section">
            <strong>Vehículo</strong>
            Marca: {{ $workOrder->vehicle?->brand ?? 'S/R' }}<br>
            Modelo: {{ $workOrder->vehicle?->model ?? 'S/R' }}<br>
            Patente: {{ $workOrder->vehicle?->license_plate ?? 'S/R' }}
        </div>
    </div>

    <strong>Insumos y Servicios</strong>
    <table>
        <thead>
            <tr>
                <th>Detalle del Ítem</th>
                <th>Cant.</th>
                <th>Precio Un.</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workOrder->items as $item)
                <tr>
                    <td>{{ $item->product?->product_name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div style="display:table-cell; width: 50%;">
            @if($workOrder->status->value === 'closed')
                <p style="font-size: 12px; color: #666;">Cierre del turno: {{ $workOrder->updated_at->format('d/m/Y H:i') }}</p>
                <p style="font-size: 12px; color: #666;">Operador: {{ $workOrder->user->name }}</p>
            @endif
        </div>
        <div class="totals-column">
            <div class="total-row" style="font-size: 18px; margin-top: 10px;"><strong>Valor Base Ticket:</strong> <span>${{ number_format($workOrder->total_amount, 2) }}</span></div>
            <p style="font-size: 10px; color: #999; margin-top:5px;">Este documento es un comprobante de servicio interno.<br>No válido como factura comercial.</p>
        </div>
    </div>

</body>
</html>
