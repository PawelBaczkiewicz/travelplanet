<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\ProductManagement\Domain\Repositories\ProductRepositoryInterface;
use Modules\ProductManagement\Infrastructure\Api\ApiClient;
use Modules\ProductManagement\Infrastructure\Persistence\EloquentProductRepository;
use Modules\ProductManagement\Infrastructure\Persistence\EloquentProductRepositoryMapper;
use Modules\ProductManagement\Domain\Repositories\ProductRepositoryMapperInterface;
use Modules\ProductManagement\Infrastructure\Bus\CommandBus;
use Modules\ProductManagement\Infrastructure\Bus\QueryBus;
use GuzzleHttp\Client;

class ProductManagementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->scoped(ProductRepositoryMapperInterface::class, EloquentProductRepositoryMapper::class);

        $this->app->scoped(ApiClient::class, function ($app) {
            return new ApiClient(client: $app->make(Client::class));
        });


        $this->app->singleton(CommandBus::class, function ($app) {
            return new CommandBus($app);
        });

        $this->app->singleton(QueryBus::class, function ($app) {
            return new QueryBus($app);
        });
    }

    public function boot(): void {}
}
