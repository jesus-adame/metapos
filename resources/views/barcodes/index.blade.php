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
            padding: 70px 20px;
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
            font-size: 60px
        }
        .barcode .price {
            font-size: 160px
        }
        .barcode .code {
            font-size: 45px
        }
        .barcode .location {
            font-size: 30px;
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
                <span class="code">{{ $barcode['code'] }}</span>
            </div>
        @endforeach
    </div>
</body>
</html>