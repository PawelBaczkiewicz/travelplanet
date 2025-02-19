<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Shared\Infrastructure\Services\Logger\ApplicationLogger;
use Symfony\Component\HttpFoundation\Response;

class LogHttpErrors
{
    public function __construct(
        private readonly ApplicationLogger $logger
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() >= 400) {
            $this->logger->logError('HTTP Error occurred', [
                'status_code' => $response->getStatusCode(),
                'path' => $request->path(),
                'method' => $request->method(),
                'user_id' => $request->user()?->id,
                'ip' => $request->ip()
            ]);
        }

        return $response;
    }
}
