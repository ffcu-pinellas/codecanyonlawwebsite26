<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class CurrencyHelper
{
    public static function rates(): array
    {
        return [
            'USD' => ['rate' => 1.00, 'symbol' => '$', 'name' => 'US Dollar'],
            'EUR' => ['rate' => 0.92, 'symbol' => '€', 'name' => 'Euro'],
            'GBP' => ['rate' => 0.79, 'symbol' => '£', 'name' => 'British Pound'],
            'CAD' => ['rate' => 1.36, 'symbol' => 'CA$', 'name' => 'Canadian Dollar'],
            'AUD' => ['rate' => 1.52, 'symbol' => 'A$', 'name' => 'Australian Dollar'],
            'CHF' => ['rate' => 0.88, 'symbol' => 'CHF', 'name' => 'Swiss Franc'],
            'JPY' => ['rate' => 155.00, 'symbol' => '¥', 'name' => 'Japanese Yen'],
        ];
    }

    public static function clientCurrency(): string
    {
        $user = Auth::user();
        $curr = $user ? ($user->preferred_currency ?: 'USD') : 'USD';
        return strtoupper(trim($curr));
    }

    public static function convert(float $amountUsd, ?string $toCurrency = null): float
    {
        $toCurrency = $toCurrency ?: self::clientCurrency();
        $rates = self::rates();
        $rate = $rates[$toCurrency]['rate'] ?? 1.00;
        return round($amountUsd * $rate, 2);
    }

    public static function format(float $amountUsd, ?string $currency = null, bool $includeUsdEquivalent = true): string
    {
        $targetCurrency = $currency ?: self::clientCurrency();
        $rates = self::rates();
        $rateInfo = $rates[$targetCurrency] ?? ['rate' => 1.00, 'symbol' => '$'];
        $converted = round($amountUsd * $rateInfo['rate'], 2);
        $symbol = $rateInfo['symbol'];

        if ($targetCurrency !== 'USD' && $includeUsdEquivalent) {
            return '<span class="font-weight-bold">' . $symbol . number_format($converted, 2) . ' ' . $targetCurrency . '</span>' .
                   '<small class="text-muted d-block" style="font-size: 11px;">≈ $' . number_format($amountUsd, 2) . ' USD</small>';
        }

        return '<span class="font-weight-bold">$' . number_format($amountUsd, 2) . '</span>';
    }
}
