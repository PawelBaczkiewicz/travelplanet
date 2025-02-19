<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Service;

use Modules\Shared\Domain\ValueObjects\Currency;
use Modules\Shared\Domain\ValueObjects\Money;

class CurrencyConverter
{
    public function convert(float $amount, Currency $from, Currency $to): Money
    {
        return new Money($amount * $this->getCurrencyConversionRate($from, $to), $to);
    }

    // will be read from external API (internet) - here is a just example
    public function getCurrencyConversionRate(Currency $from, Currency $to): float
    {
        if ($from === $to) {
            return 1;
        }

        return match ($from) {
            Currency::PLN => match ($to) {
                Currency::USD => 1 / 3.9,
                Currency::EUR => 1 / 4.1,
                Currency::GBP => 1 / 5.0,
                Currency::CHF => 1 / 4.4
            },
            Currency::USD => match ($to) {
                Currency::PLN => 3.9,
                Currency::EUR => 4.1,
                Currency::GBP => 5.0,
                Currency::CHF => 4.4,
            },
            default => 2.5
        };
    }
}
