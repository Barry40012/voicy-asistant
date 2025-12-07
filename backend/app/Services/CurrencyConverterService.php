<?php

namespace App\Services;

use Illuminate\Support\Collection;

class CurrencyConverterService
{
    /**
     * Taux de conversion (basés sur des valeurs approximatives)
     * Ces taux peuvent être mis à jour depuis l'admin plus tard
     */
    private array $rates = [
        'XOF' => [
            'USD' => 0.0017,  // 1 XOF = 0.0017 USD (environ 600 XOF = 1 USD)
            'GNF' => 0.1,      // 1 XOF = 0.1 GNF (environ 10 XOF = 1 GNF)
            'EUR' => 0.0015,  // 1 XOF = 0.0015 EUR
            'NGN' => 0.75,     // 1 XOF = 0.75 NGN
        ],
        'GNF' => [
            'USD' => 0.00012, // 1 GNF = 0.00012 USD (environ 8500 GNF = 1 USD)
            'XOF' => 10,      // 1 GNF = 10 XOF
            'EUR' => 0.00011, // 1 GNF = 0.00011 EUR
            'NGN' => 7.5,     // 1 GNF = 7.5 NGN
        ],
        'USD' => [
            'XOF' => 600,     // 1 USD = 600 XOF
            'GNF' => 8500,    // 1 USD = 8500 GNF
            'EUR' => 0.92,    // 1 USD = 0.92 EUR
            'NGN' => 1500,    // 1 USD = 1500 NGN
        ],
        'EUR' => [
            'USD' => 1.09,    // 1 EUR = 1.09 USD
            'XOF' => 655.96,  // 1 EUR = 655.96 XOF (taux fixe)
            'GNF' => 9000,    // 1 EUR = 9000 GNF (approximatif)
            'NGN' => 1600,    // 1 EUR = 1600 NGN
        ],
        'NGN' => [
            'USD' => 0.00067, // 1 NGN = 0.00067 USD
            'XOF' => 1.33,    // 1 NGN = 1.33 XOF
            'GNF' => 0.13,    // 1 NGN = 0.13 GNF
            'EUR' => 0.000625, // 1 NGN = 0.000625 EUR
        ],
    ];

    /**
     * Convertir un montant d'une devise à une autre
     */
    public function convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        if (!isset($this->rates[$fromCurrency][$toCurrency])) {
            // Si pas de taux direct, essayer via USD
            if (isset($this->rates[$fromCurrency]['USD']) && isset($this->rates['USD'][$toCurrency])) {
                $amountInUSD = $amount * $this->rates[$fromCurrency]['USD'];
                return $amountInUSD * $this->rates['USD'][$toCurrency];
            }
            
            return $amount; // Retourner le montant original si pas de conversion possible
        }

        return $amount * $this->rates[$fromCurrency][$toCurrency];
    }

    /**
     * Formater un montant avec la devise
     */
    public function format(float $amount, string $currency): string
    {
        return number_format($amount, 2, '.', ',') . ' ' . $currency;
    }

    /**
     * Obtenir les totaux par devise
     */
    public function getTotalsByCurrency(Collection|array $payments): array
    {
        $totals = [];
        
        foreach ($payments as $payment) {
            $currency = $payment->currency ?? 'XOF';
            if (!isset($totals[$currency])) {
                $totals[$currency] = 0;
            }
            $totals[$currency] += $payment->amount ?? 0;
        }
        
        return $totals;
    }

    /**
     * Convertir tous les montants vers une devise de référence
     */
    public function convertAllToCurrency(Collection|array $payments, string $targetCurrency = 'XOF'): float
    {
        $total = 0;
        
        foreach ($payments as $payment) {
            $currency = $payment->currency ?? 'XOF';
            $amount = $payment->amount ?? 0;
            $total += $this->convert($amount, $currency, $targetCurrency);
        }
        
        return $total;
    }
}

