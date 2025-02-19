<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Queries\GetProduct;

use Modules\ProductManagement\Domain\Repositories\ProductRepositoryInterface;
use Modules\ProductManagement\Domain\Repositories\ProductRepositoryMapperInterface;
use Modules\ProductManagement\Domain\Entities\Product;

final readonly class GetProductQueryHandler
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private ProductRepositoryMapperInterface $mapper
    ) {}

    public function handle(GetProductQuery $query): Product|null
    {
        $eloquentProduct = $this->repository->getProductById($query->id);
        return $eloquentProduct ? $this->mapper->toDomainProduct($eloquentProduct) : null;
    }
}
