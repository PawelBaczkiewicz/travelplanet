<?php

declare(strict_types=1);

namespace Modules\Shared\Application\DTOs;

use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\Shared\Domain\ValueObjects\Money;
use Modules\Shared\Domain\ValueObjects\Currency;
use Modules\Shared\Application\DTOs\RequestProductDTOInterface;
use Modules\PaymentScheduleAPI\Domain\Entities\Product;
use DateTimeImmutable;

final readonly class RequestProductInstallmentsDTO implements RequestProductDTOInterface
{
    public function __construct(
        public string $type,
        public float $priceAmount,
        public string $priceCurrency,
        public string $soldDate
    ) {}

    public static function createFromArray(array $data): static
    {
        return new static(
            type: $data['type'],
            priceAmount: $data['priceAmount'],
            priceCurrency: $data['priceCurrency'],
            soldDate: $data['soldDate'],
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'priceAmount' => $this->priceAmount,
            'priceCurrency' => $this->priceCurrency,
            'soldDate' => $this->soldDate,
        ];
    }

    public function toEntity(): Product
    {
        return new Product(
            type: ProductType::from($this->type),
            price: new Money($this->priceAmount, Currency::from($this->priceCurrency)),
            soldDate: new DateTimeImmutable($this->soldDate),
        );
    }
}
