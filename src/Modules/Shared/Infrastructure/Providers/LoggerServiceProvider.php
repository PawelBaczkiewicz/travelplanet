<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Shared\Infrastructure\Services\Logger\ApplicationLogger;

class LoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ApplicationLogger::class, function () {
            return new ApplicationLogger();
        });
    }
}
