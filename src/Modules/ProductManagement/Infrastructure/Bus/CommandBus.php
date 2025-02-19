<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Infrastructure\Bus;

use Illuminate\Container\Container;
use Modules\Shared\Infrastructure\Facades\Log;

final readonly class CommandBus
{
    public function __construct(
        private Container $container
    ) {}

    public function dispatch(object $command): void
    {
        $commandClass = get_class($command);
        $handlerClass = preg_replace('/Command$/', 'CommandHandler', $commandClass);

        try {
            $handler = $this->container->make($handlerClass);
            $handler->handle($command);
            Log::logDebug('Command handled successfully', [
                'command' => class_basename(get_class($command)),
                'handler' => class_basename($handlerClass)
            ]);
        } catch (\Throwable $e) {
            Log::logError('Failed to handle command', [
                'command' => class_basename(get_class($command)),
                'handler' => class_basename($handlerClass),
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
