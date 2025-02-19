<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObjects;

use Modules\Shared\Domain\ValueObjects\Money;

use DateTimeImmutable;

final class PaymentInstallment implements \JsonSerializable
{
    public function __construct(
        private readonly Money $price,
        private readonly DateTimeImmutable $dueDate
    ) {}

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getAmount(): float
    {
        return $this->price->getAmount();
    }

    public function getCurrency(): Currency
    {
        return $this->price->getCurrency();
    }


    public function getDueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency()->toString(),
            'dueDate' => $this->getDueDateUTC()
        ];
    }

    public function getDueDateUTC(): string
    {
        return $this->dueDate->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    }

    public function getDueDateIso8601(): string
    {
        return $this->dueDate->format(\DateTime::ATOM);
    }
}
