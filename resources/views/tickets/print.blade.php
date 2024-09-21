<html>
<head>
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
            margin-bottom: 5px;
            font-size: 14px;
        }
        .header p {
            margin: 0;
            font-size: 10px;
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
        }
        .total p {
            font-weight: bold;
        }
        .total span {
            display: block;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .footer p {
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="ticket" style="width: {{$ancho_mm}}mm">
        <div class="header">
            <h1>{{ $company_name }}</h1>
            <p>{{ $company_address }}</p>
            <p>Teléfono: {{ $company_phone }}</p>
            <p>Rfc: {{ $company_rfc }}</p>
        </div>
        <div class="content">
            <div class="details">
                <p>Cliente: {{ $sale->customer?->name }} {{ $sale->customer?->lastname }}</p>
                <p>Vendedor: {{ $sale->seller?->name }} {{ $sale->customer?->lastname }}</p>
                <p>Fecha: {{ $date->format('d/m/Y h:i a') }}</p>
            </div>
            <div class="products">
                <table>
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Precio</th>
                            <th>Cant</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td style="text-align: right">{{ number_format($product->pivot->price + $product->pivot->tax, 2) }}</td>
                                <td style="text-align: right">{{ $product->pivot->quantity }}</td>
                                <td style="text-align: right">{{ number_format($product->pivot->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="total">
                <span style="font-size: 10px">IVA: ${{ number_format($taxes, 2) }}</span>
                <p>Total: ${{ number_format($sale->total, 2) }}</p>
                <span>Pago: ${{ number_format($totalPayments, 2) }}</span>
                <span>Cambio: ${{ number_format($sale->change, 2) }}</span>
            </div>
        </div>
        <div class="footer">
            <p>¡Gracias por su compra!</p>
            <p>Lo esperamos nuevamente</p>
        </div>
    </div>
</body>
</html>
