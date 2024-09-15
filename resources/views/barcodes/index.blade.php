<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Código de barras</title>
    <style>
        * {
            margin: 0%;
            padding: 0%;
            box-sizing: border-box;
        }
        .content {
        }
        .barcode {
            padding: 2px;
            border: 1px solid;
            border-color: #a5a5a5;
            text-align: center;
            border-radius: 5px;
            width: 160px;
            width: 38mm;
        }
        .barcode .name {
            font-size: 13px
        }
    </style>
</head>
<body>
    <div class="content">
        @foreach ($barcodes as $barcode)
            <div class="barcode" style="display: {{ $style }}">
                <span class="name">{{ $barcode['name'] }}</span>
                <h2>${{ $barcode['price'] }}</h2>
                <div style="margin: auto; display: inline-block">
                    {!! $barcode['bars'] !!}
                </div><br>
                <span style="font-size: 12px">{{ $barcode['code'] }}</span>
            </div>
        @endforeach
    </div>
</body>
</html>