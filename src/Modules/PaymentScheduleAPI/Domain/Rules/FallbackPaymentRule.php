<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Domain\Rules;

use Modules\Shared\Domain\Service\CurrencyConverter;
use Modules\Shared\Domain\ValueObjects\Currency;
use Modules\Shared\Domain\ValueObjects\PaymentInstallment;
use Modules\PaymentScheduleAPI\Domain\Entities\Product;
use Modules\Shared\Domain\ValueObjects\Money;

final readonly class FallbackPaymentRule implements PaymentRuleInterface
{
    public function __construct(private CurrencyConverter $currencyConverter) {}

    public function isApplicable(Product $product): bool
    {
        return true;
    }

    public function calculate(Product $product): array
    {
        // Example of how to convert price if the response is in different currency
        // than the product original currency

        // $convertedPrice = $this->currencyConverter->convert(
        //     amount: $product->price->getAmount(),
        //     from: $product->price->getCurrency(),
        //     to: $this->getCurrency()
        // );

        // return [
        //     new PaymentInstallment(
        //         $convertedPrice,
        //         $product->soldDate
        //     )
        // ];

        return [
            new PaymentInstallment(
                $product->price,
                $product->soldDate
            ),
        ];
    }

    public function getCurrency(): Currency
    {
        return Currency::PLN;
    }
}
