<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Infrastructure\Bus;

use Illuminate\Container\Container;
use Modules\Shared\Infrastructure\Facades\Log;

final readonly class QueryBus
{
    public function __construct(
        private Container $container
    ) {}

    public function ask(object $query): mixed
    {
        $queryClass = get_class($query);
        $handlerClass = preg_replace('/Query$/', 'QueryHandler', $queryClass);

        try {
            $handler = $this->container->make($handlerClass);
            $result = $handler->handle($query);
            Log::logDebug('Query handled successfully', [
                'query' => class_basename(get_class($query)),
                'handler' => class_basename($handlerClass)
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::logError('Failed to handle query', [
                'query' => class_basename(get_class($query)),
                'handler' => class_basename($handlerClass),
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
