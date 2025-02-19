<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Infrastructure\Services;

use Illuminate\Support\Facades\Route;

class ApiVersionService
{
    private const DEFAULT_VERSION = 'v1';

    public function getApiVersion(): string
    {
        $prefix = Route::current()?->getPrefix() ?? '';
        return str_contains($prefix, 'v2') ? 'v2' : self::DEFAULT_VERSION;
    }
}
