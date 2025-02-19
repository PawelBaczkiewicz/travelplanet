<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Domain\Entities;

use Modules\Shared\Domain\ValueObjects\Money;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\Shared\Domain\Entities\ProductInterface;
use DateTimeImmutable;
use Modules\Shared\Domain\ValueObjects\PaymentInstallment;
use Ramsey\Uuid\UuidInterface;

final class Product implements ProductInterface
{
    /** @var PaymentInstallment[] */
    private array $paymentInstallments;
    public function __construct(
        public ?UuidInterface $id = null,
        public string $name,
        public ProductType $type,
        public Money $price,
        public DateTimeImmutable $soldDate
    ) {
        $this->paymentInstallments = [];
    }

    /** @param PaymentInstallment[] $paymentInstallments */
    public function setPaymentInstallments(array $paymentInstallments): void
    {
        $this->paymentInstallments = $paymentInstallments;
    }


    /** @return PaymentInstallment[] */
    public function getPaymentInstallments(): array
    {
        return $this->paymentInstallments;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'name' => $this->name,
            'type' => $this->type->value,
            'priceAmount' => $this->price->getAmount(),
            'priceCurrency' => $this->price->getCurrency(),
            'soldDate' => $this->getSoldDateUTC(),
            'paymentInstallments' => array_map(
                fn(PaymentInstallment $installment)
                => $installment->jsonSerialize(),
                $this->paymentInstallments
            )
        ];
    }

    public function getSoldDateUTC(): string
    {
        return $this->soldDate->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    }

    public function getSoldDateIso8601(): string
    {
        return $this->soldDate->format(\DateTime::ATOM);
    }
}
