<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Application\DTOs;

final readonly class ResponsePaymentScheduleDTO implements \JsonSerializable
{
    public string $timestamp;

    public function __construct(
        public string $apiVersion,
        public bool $success,
        public ?array $data = null,
        public ?ErrorDTO $error = null
    ) {
        $this->timestamp = now()->toIso8601String();
    }

    public static function success(
        string $apiVersion,
        array $data
    ): self {
        return new self(
            apiVersion: $apiVersion,
            success: true,
            data: $data
        );
    }

    public static function error(
        string $apiVersion,
        int $errorCode,
        ?string $errorMessage = null,
        ?array $errorDetails = null
    ): self {
        return new self(
            apiVersion: $apiVersion,
            success: false,
            error: new ErrorDTO(
                code: $errorCode,
                message: $errorMessage,
                details: $errorDetails
            )
        );
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'apiVersion' => $this->apiVersion,
            'success' => $this->success,
            'data' => $this->data,
            'error' => $this->error?->jsonSerialize(),
            'timestamp' => $this->timestamp
        ], fn($value) => $value !== null);
    }
}
