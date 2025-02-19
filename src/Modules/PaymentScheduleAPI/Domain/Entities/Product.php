<?php

namespace Modules\PaymentScheduleAPI\Domain\Entities;

use Modules\Shared\Domain\ValueObjects\Money;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\Shared\Domain\Entities\ProductInterface;
use DateTimeImmutable;

final class Product implements ProductInterface
{
    public function __construct(
        public ProductType $type,
        public Money $price,
        public DateTimeImmutable $soldDate
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'priceAmount' => $this->price->getAmount(),
            'priceCurrency' => $this->price->getCurrency(),
            'soldDate' => $this->soldDate->format(\DateTime::ATOM)
        ];
    }
}
