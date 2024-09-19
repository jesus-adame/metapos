<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Currencies\CurrencyConversionService;
use App\Models\Currency;
use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyConversionService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $currencies = Currency::orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($currencies);
    }

    public function convert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'from' => 'required|string|size:3',
            'to' => 'required|string|size:3',
        ]);

        $amount = $request->input('amount');
        $fromCurrency = $request->input('from');
        $toCurrency = $request->input('to');

        try {
            $convertedAmount = $this->currencyService->convert($amount, $fromCurrency, $toCurrency);
            return response()->json([
                'original' => [
                    'amount' => $amount,
                    'currency' => $fromCurrency
                ],
                'converted' => [
                    'amount' => $convertedAmount,
                    'currency' => $toCurrency
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateRate(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|size:3',
            'rate' => 'required|numeric',
        ]);

        $currencyCode = $request->input('currency');
        $newRate = $request->input('rate');

        try {
            $this->currencyService->updateExchangeRate($currencyCode, $newRate);
            return response()->json(['message' => 'Exchange rate updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function refreshRates()
    {
        try {
            $this->currencyService->refreshExchangeRates();
            return response()->json(['message' => 'Exchange rate updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
