<?php

namespace App\Http\Controllers\WorkOrders;

use App\Http\Controllers\Controller;
use App\Src\WorkOrders\Models\WorkOrder;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkOrderPdfController extends Controller
{
    public function download(WorkOrder $workOrder)
    {
        $workOrder->load(['customer', 'vehicle', 'items.product', 'user']);

        $pdf = Pdf::loadView('pdf.work-order', [
            'workOrder' => $workOrder,
        ]);

        return $pdf->download('orden_trabajo_'.str_pad($workOrder->id, 5, '0', STR_PAD_LEFT).'.pdf');
    }
}
