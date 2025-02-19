<?php

namespace Modules\Shared\Infrastructure\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Shared\Infrastructure\Facades\Log;

class ResponseTimeMiddleware
{
    private const EXPECTED_RESPONSE_TIME = 1000;

    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment(['local', 'development', 'testing'])) {
            $start = microtime(true);

            $response = $next($request);

            $timeMs = (microtime(true) - $start) * 1000;

            if ($timeMs > self::EXPECTED_RESPONSE_TIME) {
                Log::logWarning('Payment schedule calculation started', [
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'expected_ms' => self::EXPECTED_RESPONSE_TIME,
                    'actual_ms' => round($timeMs, 2)
                ]);
            }

            $response->headers->set('X-Response-Time', sprintf('%.2fms', $timeMs));
        } else {
            $response = $next($request);
        }

        return $response;
    }
}
