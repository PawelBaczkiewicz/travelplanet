<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Domain\Rules;

use Modules\Shared\Domain\ValueObjects\PaymentInstallment;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\PaymentScheduleAPI\Domain\Entities\Product;
use Modules\Shared\Domain\ValueObjects\Currency;

final class PremiumPaymentRule implements PaymentRuleInterface
{
    public function isApplicable(Product $product): bool
    {
        return $product->type === ProductType::PREMIUM;
    }

    public function calculate(Product $product): array
    {
        return [
            new PaymentInstallment(
                $product->price,
                $product->soldDate->modify('+3 month')->setTime(23, 59, 59)
            )
        ];
    }
    public function getCurrency(): Currency
    {
        return Currency::EUR;
    }
}
