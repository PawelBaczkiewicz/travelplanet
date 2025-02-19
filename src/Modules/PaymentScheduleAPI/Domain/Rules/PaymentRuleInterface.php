<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Domain\Rules;

use Modules\PaymentScheduleAPI\Domain\Entities\Product;
use Modules\Shared\Domain\ValueObjects\Currency;

interface PaymentRuleInterface
{
    public function isApplicable(Product $product): bool;
    public function calculate(Product $product): array;
    public function getCurrency(): Currency;
}
