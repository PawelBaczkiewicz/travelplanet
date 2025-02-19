<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Domain\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Modules\ProductManagement\Domain\Entities\Product;
use App\Models\Product as EloquentProduct;

interface ProductRepositoryMapperInterface
{
    public function toDomainProduct(EloquentProduct $eloquentProduct): Product;

    /**
     * @return Collection<int, Product>
     */
    public function toDomainProducts(EloquentCollection $eloquentProducts): Collection;
}
