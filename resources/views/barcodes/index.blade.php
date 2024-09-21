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
            padding: 3px 10px;
            border: 1px solid;
            border-color: #d0d0d0;
            text-align: center;
            width: 50mm;
        }
        .barcode .bars {
            margin: auto;
        }
        .barcode .bars img {
            width: 80%;
        }
        .barcode .name {
            font-size: 12px
        }
        .barcode .price {
            font-size: 24px
        }
        .barcode .location {
            font-size: 8px;
            text-transform: uppercase
        }
    </style>
</head>
<body>
    <div class="content">
        @foreach ($barcodes as $barcode)
            <div class="barcode" style="display: {{ $style }}">
                <p class="location">{{ $barcode['location'] }}</p>
                <span class="name">{{ $barcode['name'] }}</span>
                <p class="price">{{ $barcode['price'] }}</p>
                <div class="bars">
                    <img src="data:image/png;base64,{{ base64_encode($barcode['bars']) }}">
                </div>
                <span style="font-size: 12px">{{ $barcode['code'] }}</span>
            </div>
        @endforeach
    </div>
</body>
</html>