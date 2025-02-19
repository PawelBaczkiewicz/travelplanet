<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\DTOs;

use Modules\Shared\Application\DTOs\RequestProductDTOInterface;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\Shared\Domain\ValueObjects\Money;
use Modules\Shared\Domain\ValueObjects\Currency;
use Modules\ProductManagement\Domain\Entities\Product;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

final readonly class RequestProductDTO implements RequestProductDTOInterface
{
    public function __construct(
        public string $name,
        public string $type,
        public float $priceAmount,
        public string $priceCurrency,
        public string $soldDate,
        public ?UuidInterface $id = null,
    ) {}

    public static function createFromArray(array $data): static
    {
        return new static(
            id: $data['id'] ?? null,
            name: $data['name'],
            type: $data['type'],
            priceAmount: $data['priceAmount'],
            priceCurrency: $data['priceCurrency'],
            soldDate: $data['soldDate']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'priceAmount' => $this->priceAmount,
            'priceCurrency' => $this->priceCurrency,
            'soldDate' => $this->soldDate,
        ];
    }

    public function toEntity(): Product
    {
        return new Product(
            id: $this->id,
            name: $this->name,
            type: ProductType::from($this->type),
            price: new Money($this->priceAmount, Currency::from($this->priceCurrency)),
            soldDate: new DateTimeImmutable($this->soldDate)
        );
    }
}
