<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Services\Logger;

use Illuminate\Support\Facades\Log;
use Throwable;

final class ApplicationLogger
{
    public function logCritical(string $message, Throwable $exception, array $context = []): void
    {
        Log::channel('travelplanet')->critical($message, [
            'exception' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ],
            ...$context
        ]);
    }

    public function logError(string $message, array $context = []): void
    {
        Log::channel('travelplanet')->error($message, $context);
    }

    public function logWarning(string $message, array $context = []): void
    {
        Log::channel('travelplanet')->warning($message, $context);
    }

    public function logInfo(string $message, array $context = []): void
    {
        Log::channel('travelplanet')->info($message, $context);
    }

    public function logDebug(string $message, array $context = []): void
    {
        if (app()->environment(['local', 'development', 'testing'])) {
            Log::channel('travelplanet')->debug($message, $context);
        }
    }
}
