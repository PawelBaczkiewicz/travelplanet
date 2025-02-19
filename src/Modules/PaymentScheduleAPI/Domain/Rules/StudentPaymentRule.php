<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Domain\Rules;

use Modules\Shared\Domain\ValueObjects\PaymentInstallment;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\PaymentScheduleAPI\Domain\Entities\Product;
use Modules\Shared\Domain\ValueObjects\Currency;

final class StudentPaymentRule implements PaymentRuleInterface
{
    public function isApplicable(Product $product): bool
    {
        return $product->type === ProductType::STUDENT;
    }

    public function calculate(Product $product): array
    {
        $price0_33 = $product->price->multiply(0.33);
        $price0_34 = $product->price->subtract($price0_33->add($price0_33));

        return [
            new PaymentInstallment(
                $price0_33,
                $product->soldDate->modify('+3 days')->setTime(23, 59, 59)
            ),
            new PaymentInstallment(
                $price0_33,
                $product->soldDate->modify('+1 month')->setTime(23, 59, 59)
            ),
            new PaymentInstallment(
                $price0_34,
                $product->soldDate->modify('+2 month')->setTime(23, 59, 59)
            )
        ];
    }
    public function getCurrency(): Currency
    {
        return Currency::PLN;
    }
}
