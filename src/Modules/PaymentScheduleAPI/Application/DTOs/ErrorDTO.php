<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Application\DTOs;

readonly class ErrorDTO implements \JsonSerializable
{
    public function __construct(
        public int $code,
        public ?string $message = null,
        public ?array $details = null
    ) {}

    public function jsonSerialize(): array
    {
        return array_filter([
            'code' => $this->code,
            'message' => $this->message,
            'details' => $this->details
        ], fn($value) => $value !== null);
    }
}
