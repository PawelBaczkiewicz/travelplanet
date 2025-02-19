<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Domain\Rules;

use Modules\Shared\Domain\ValueObjects\PaymentInstallment;
use Modules\PaymentScheduleAPI\Domain\Entities\Product;
use Modules\Shared\Domain\ValueObjects\Currency;

final class JunePaymentRule implements PaymentRuleInterface
{
    public function isApplicable(Product $product): bool
    {
        return $product->soldDate->format('n') === '6';
    }

    public function calculate(Product $product): array
    {
        $price0_3 = $product->price->multiply(0.3);
        $price0_7 = $product->price->subtract($price0_3);

        return [
            new PaymentInstallment(
                $price0_3,
                $product->soldDate
            ),
            new PaymentInstallment(
                $price0_7,
                $product->soldDate->modify('last day of +3 months')->setTime(23, 59, 59)
            )
        ];
    }
    public function getCurrency(): Currency
    {
        return Currency::PLN;
    }
}
