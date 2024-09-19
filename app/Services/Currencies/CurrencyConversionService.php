<?php

namespace App\Services\Currencies;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Currency;

class CurrencyConversionService
{
    protected $apiUrl;
    protected $baseCurrency; // Moneda base para las conversiones
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('app.exchange_api.base_url');
        $this->baseCurrency = config('app.exchange_api.base_currency');
        $this->apiKey = config('app.exchange_api.key');
    }

    public function convert($amount, $fromCurrencyCode, $toCurrencyCode)
    {
        if ($fromCurrencyCode === $toCurrencyCode) {
            return $amount;
        }

        $fromCurrency = $this->getCurrency($fromCurrencyCode);
        $toCurrency = $this->getCurrency($toCurrencyCode);

        $amountInBaseCurrency = $amount / $fromCurrency->exchange_rate;
        $convertedAmount = $amountInBaseCurrency * $toCurrency->exchange_rate;

        return round($convertedAmount, 2);
    }

    protected function getCurrency($currencyCode)
    {
        $currency = Currency::where('code', $currencyCode)->first();

        if (!$currency) {
            throw new \Exception("Currency not found: $currencyCode");
        }

        return $currency;
    }

    public function updateExchangeRate($currencyCode, $newRate)
    {
        $currency = $this->getCurrency($currencyCode);
        $currency->exchange_rate = $newRate;
        $currency->save();
    }

    public function refreshExchangeRates()
    {
        $rates = [];

        $response = Http::get($this->apiUrl . '/v6/' . $this->apiKey . '/latest/' . $this->baseCurrency);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch exchange rates');
        }

        $rates = $response->json()['conversion_rates'];

        foreach ($rates as $code => $rate) {
            Currency::updateOrCreate(
                ['code' => $code],
                [
                    'exchange_rate' => $rate,
                    'name' => $code, // Idealmente, tendrías un mapeo de códigos a nombres completos
                    'symbol' => $code, // Idealmente, tendrías un mapeo de códigos a símbolos
                ]
            );
        }
    }
}
