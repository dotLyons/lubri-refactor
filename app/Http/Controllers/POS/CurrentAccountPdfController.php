<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Src\Invoices\Models\InvoicePayment;
use Barryvdh\DomPDF\Facade\Pdf;

class CurrentAccountPdfController extends Controller
{
    public function download(InvoicePayment $payment)
    {
        $payment->load(['invoice.customer', 'invoice.workOrder.vehicle', 'user']);

        $pdf = Pdf::loadView('pdf.current-account-payment', [
            'payment' => $payment,
        ]);

        return $pdf->download('cuenta_corriente_'.str_pad($payment->id, 5, '0', STR_PAD_LEFT).'.pdf');
    }
}
