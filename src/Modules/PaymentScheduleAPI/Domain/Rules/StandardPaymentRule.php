<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Domain\Rules;

use Modules\Shared\Domain\ValueObjects\PaymentInstallment;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\PaymentScheduleAPI\Domain\Entities\Product;
use Modules\Shared\Domain\ValueObjects\Currency;

final class StandardPaymentRule implements PaymentRuleInterface
{
    public function isApplicable(Product $product): bool
    {
        return $product->type === ProductType::STANDARD;
    }

    public function calculate(Product $product): array
    {
        $price0_5_1 = $product->price->multiply(0.5);
        $price0_5_2 = $product->price->subtract($price0_5_1);

        return [
            new PaymentInstallment(
                $price0_5_1,
                $product->soldDate
            ),
            new PaymentInstallment(
                $price0_5_2,
                $product->soldDate->modify('+1 month')->setTime(23, 59, 59)
            )
        ];
    }

    public function getCurrency(): Currency
    {
        return Currency::PLN;
    }
}
