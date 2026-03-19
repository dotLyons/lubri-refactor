<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta Corriente #{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #ddd; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #4F46E5; }
        .info-container { display: table; width: 100%; margin-bottom: 20px; }
        .info-section { display: table-cell; width: 50%; padding-right: 20px; }
        .info-section strong { display: block; margin-bottom: 5px; color: #666; font-size: 12px; text-transform: uppercase; }
        .amount-box { background: #f0fdf4; border: 2px solid #10B981; border-radius: 8px; padding: 20px; text-align: center; margin: 20px 0; }
        .amount-box .label { font-size: 12px; text-transform: uppercase; color: #666; margin-bottom: 5px; }
        .amount-box .amount { font-size: 32px; font-weight: bold; color: #10B981; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; text-align: center; }
        .signature-box { margin-top: 60px; display: table; width: 100%; }
        .signature-section { display: table-cell; width: 50%; text-align: center; padding: 20px; }
        .signature-line { border-top: 1px solid #333; margin-top: 40px; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Centro de Servicios Multipolar</h1>
        <p>Comprobante de Pago - Cuenta Corriente<br>
        #{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}<br>
        Fecha: {{ $payment->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-container">
        <div class="info-section">
            <strong>Datos del Cliente</strong>
            Nombre: {{ $payment->invoice->customer->first_name }} {{ $payment->invoice->customer->last_name }}<br>
            DNI/CUIT: {{ $payment->invoice->customer->dni }}<br>
            Tel: {{ $payment->invoice->customer->phone ?? 'N/A' }}
        </div>
        <div class="info-section">
            <strong>Datos del Servicio</strong>
            @if($payment->invoice->workOrder)
                Orden de Trabajo: #{{ str_pad($payment->invoice->work_order_id, 5, '0', STR_PAD_LEFT) }}<br>
                Vehículo: {{ $payment->invoice->workOrder->vehicle?->brand ?? 'S/R' }} {{ $payment->invoice->workOrder->vehicle?->model ?? '' }}<br>
                Patente: {{ $payment->invoice->workOrder->vehicle?->license_plate ?? 'S/R' }}<br>
                Turno: {{ $payment->invoice->workOrder->scheduled_at->format('d/m/Y H:i') }}
            @else
                Factura: #{{ str_pad($payment->invoice_id, 5, '0', STR_PAD_LEFT) }}
            @endif
        </div>
    </div>

    <div class="amount-box">
        <div class="label">Monto Abonado por Cuenta Corriente</div>
        <div class="amount">${{ number_format($payment->amount, 2) }}</div>
    </div>

    <div class="info-container">
        <div class="info-section">
            <strong>Método de Pago</strong>
            {{ $payment->payment_method->label() }}
        </div>
        <div class="info-section">
            <strong>Registrado por</strong>
            {{ $payment->user->name }}
        </div>
    </div>

    <div class="signature-box">
        <div class="signature-section">
            <div class="signature-line">Firma del Cliente</div>
        </div>
        <div class="signature-section">
            <div class="signature-line">Firma y Sello</div>
        </div>
    </div>

    <div class="footer">
        <p>Este comprobante es parte de la factura #{{ str_pad($payment->invoice_id, 5, '0', STR_PAD_LEFT) }}</p>
        <p>Centro de Servicios Multipolar - Todos los derechos reservados</p>
    </div>

</body>
</html>
