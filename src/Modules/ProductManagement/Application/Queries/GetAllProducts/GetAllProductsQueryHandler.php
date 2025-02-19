<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Queries\GetAllProducts;

use Modules\ProductManagement\Domain\Repositories\ProductRepositoryInterface;
use Modules\ProductManagement\Domain\Repositories\ProductRepositoryMapperInterface;
use Illuminate\Support\Collection;

final readonly class GetAllProductsQueryHandler
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private ProductRepositoryMapperInterface $mapper
    ) {}

    public function handle(GetAllProductsQuery $query): Collection
    {
        $eloquentProducts = $this->repository->getAllProductsWithInstallments();
        return $this->mapper->toDomainProducts($eloquentProducts);
    }
}
