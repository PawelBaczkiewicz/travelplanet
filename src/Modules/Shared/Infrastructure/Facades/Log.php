<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Shared\Infrastructure\Services\Logger\ApplicationLogger;

/**
 * @method static void logCritical(string $message, \Throwable $exception, array $context = [])
 * @method static void logError(string $message, array $context = [])
 * @method static void logWarning(string $message, array $context = [])
 * @method static void logInfo(string $message, array $context = [])
 * @method static void logDebug(string $message, array $context = [])
 */
class Log extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ApplicationLogger::class;
    }
}
