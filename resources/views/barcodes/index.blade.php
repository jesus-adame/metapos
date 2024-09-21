<html>
<head>
    <title>Código de barras</title>
    <style>
        * {
            margin: 0%;
            padding: 0%;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif
        }
        .content {
        }
        .barcode {
            padding-top: 90px;
            text-align: center;
            width: 100%;
        }
        .barcode .bars {
            margin: auto;
        }
        .barcode .bars img {
            width: 75%;
        }
        .barcode .name {
            font-size: 70px
        }
        .barcode .price {
            font-size: 180px
        }
        .barcode .code {
            font-size: 50px
        }
        .barcode .location {
            font-size: 35px;
            text-transform: uppercase
        }
    </style>
</head>
<body>
    <div class="content">
        @foreach ($barcodes as $index => $barcode)
            <div class="barcode" style="display: {{ $style }}">
                <p class="location">{{ $barcode['location'] }}</p>
                <span class="name">{{ $barcode['name'] }}</span>
                <p class="price">{{ $barcode['price'] }}</p>
                <div class="bars">
                    <img src="data:image/png;base64,{{ base64_encode($barcode['bars']) }}">
                </div>
                <span class="code">{{ $barcode['code'] }}</span>
            </div>
            @if (($index + 1) < count($barcodes))
                <div style="page-break-after:always;"></div>
            @endif
        @endforeach
    </div>
</body>
</html>