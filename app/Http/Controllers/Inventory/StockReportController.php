<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Src\Inventory\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function download(Request $request)
    {
        $query = Product::with(['category', 'subcategory', 'stock']);

        // Filter by Category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Subcategory
        if ($request->has('subcategory_id') && $request->subcategory_id) {
            $query->where('subcategory_id', $request->subcategory_id);
        }

        // Filter by Stock Status
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'with_stock':
                    $query->whereHas('stock', function ($q) {
                        $q->where('quantity', '>', 0);
                    });
                    break;
                case 'no_stock':
                    $query->whereDoesntHave('stock', function ($q) {
                        $q->where('quantity', '>', 0);
                    })->orWhereHas('stock', function ($q) {
                        $q->where('quantity', '<=', 0);
                    });
                    break;
                case 'all':
                default:
                    // No filter needed
                    break;
            }
        }

        $products = $query->orderBy('product_name')->get();

        $pdf = Pdf::loadView('inventory.reports.stock', compact('products'));

        return $pdf->download('reporte_stock_' . date('Y-m-d_H-i') . '.pdf');
    }
}
