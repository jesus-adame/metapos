<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .ticket {
            padding: 0px;
            margin: 6mm auto;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
        }
        .header p {
            margin: 0;
            font-size: 12px;
        }
        .content {
            margin-top: 10px;
        }
        .content .details {
            margin-bottom: 15px;
        }
        .content .details p {
            margin: 0;
        }
        .products table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .products table th, .products table td {
            border-bottom: 1px solid #000;
            padding: 2px;
            text-align: left;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        .footer p {
            margin: 0;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="ticket" style="width: {{$ancho_mm}}mm">
        <div class="header">
            <h1>Mi Empresa</h1>
            <p>Dirección de la empresa</p>
            <p>Teléfono: (123) 456-7890</p>
        </div>

        <div class="content">
            <div class="details">
                <p>Cliente: {{ $sale->customer?->name }}</p>
                <p>Vendedor: {{ $sale->seller?->name }}</p>
                <p>Fecha: {{ $sale->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div class="products">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cant.</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>{{ number_format($product->pivot->price, 2) }}</td>
                                <td>{{ number_format($product->pivot->quantity * $product->pivot->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="total">
                <p>Total: ${{ number_format($sale->total, 2) }}</p>
            </div>
        </div>

        <div class="footer">
            <p>¡Gracias por su compra!</p>
            <p>Visítenos nuevamente</p>
        </div>
    </div>
</body>
</html>
