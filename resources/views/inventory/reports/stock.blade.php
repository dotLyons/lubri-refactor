<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
        }

        .header-info {
            text-align: right;
            margin-bottom: 10px;
            font-size: 10px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .no-stock {
            color: #d9534f;
            font-weight: bold;
        }

        .low-stock {
            color: #f0ad4e;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            padding: 10px 0;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="header-info">
        Generado el: {{ date('d/m/Y H:i') }}
    </div>

    <h1>Reporte de Inventario</h1>

    <table>
        <thead>
            <tr>
                <th>Código/Barra</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th class="text-right">Costo</th>
                <th class="text-right">Precio Venta</th>
                <th class="text-center">Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>
                        {{ $product->product_code }}
                        @if($product->bar_code)
                            <br><small>{{ $product->bar_code }}</small>
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->category->category_name ?? '-' }}</td>
                    <td>{{ $product->subcategory->subcategory_name ?? '-' }}</td>
                    <td class="text-right">${{ number_format($product->cost_price, 2) }}</td>
                    <td class="text-right">${{ number_format($product->sale_price, 2) }}</td>
                    <td class="text-center">
                        @php
                            $stock = $product->stock->quantity ?? 0;
                        @endphp
                        <span class="{{ $stock <= 0 ? 'no-stock' : ($stock <= 10 ? 'low-stock' : '') }}">
                            {{ $stock }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Reporte generado automáticamente por el sistema de gestión.
    </div>
</body>

</html>