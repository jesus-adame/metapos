<?php

namespace App\Http\Controllers;

use Picqer\Barcode\BarcodeGeneratorHTML;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use App\Http\Controllers\Controller;

class BarcodeController extends Controller
{
    public function index(BarcodeGeneratorHTML $generator)
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        $barcodes = [];

        foreach ($products as $product) {
            $barcode = [
                'bars' => $generator->getBarcode($product->code, $generator::TYPE_CODE_128, 1),
                'code' => $product->code,
                'name' => $product->name,
                'price' => $product->price,
            ];
            $barcodes[] = $barcode;
        }

        $style = 'block'; // block

        // return view('barcodes.index', [
        //     'barcodes' => $barcodes,
        // ]);

        $pdf = Pdf::loadView('barcodes.index', [
            'barcodes' => $barcodes,
            'style' => $style
        ]);

        return $pdf->stream('barcodes.pdf');
    }

    public function show(BarcodeGeneratorHTML $generator, $productId, $quantity)
    {
        $product = Product::find($productId);
        $barcodes = [];

        $barcode = [
            'bars' => $generator->getBarcode($product->code, $generator::TYPE_CODE_128, 1),
            'code' => $product->code,
            'name' => $product->name,
            'price' => $product->price,
        ];

        for ($i = 1; $i <= $quantity; $i++) {
            $barcodes[] = $barcode;
        }

        $style = 'block'; // block

        // return view('barcodes.index', [
        //     'barcodes' => $barcodes,
        // ]);

        $pdf = Pdf::loadView('barcodes.index', [
            'barcodes' => $barcodes,
            'style' => $style
        ]);

        return $pdf->stream('barcodes.pdf');
    }
}
