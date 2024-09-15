<?php

namespace App\Http\Controllers;

use Picqer\Barcode\BarcodeGeneratorSVG;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Helpers\MathNumberHelper;

class BarcodeController extends Controller
{
    public function index(BarcodeGeneratorHTML $generator)
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        $barcodes = [];

        foreach ($products as $product) {
            $barcode = [
                'bars' => $generator->getBarcode($product->code, $generator::TYPE_CODE_128, 2, 35),
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

    public function show(BarcodeGeneratorPNG $generator, $productId, $quantity)
    {
        $product = Product::find($productId);
        $barcodes = [];

        $barcode = [
            'location' => Auth::user()->location->name,
            'bars' => $generator->getBarcode($product->code, $generator::TYPE_CODE_128, 3, 50),
            'code' => $product->code,
            'name' => $product->name,
            'price' => MathNumberHelper::formatMoneyFormat($product->final_price),
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
