<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Src\Invoices\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function download(Invoice $invoice)
    {
        $invoice->load(['customer', 'workOrder.vehicle', 'workOrder.items.product', 'payments.cardPlan.card']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
        ]);

        return $pdf->download('factura_'.str_pad($invoice->id, 5, '0', STR_PAD_LEFT).'.pdf');
    }
}
