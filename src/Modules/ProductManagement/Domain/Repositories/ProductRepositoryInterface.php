<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Domain\Repositories;

use Modules\ProductManagement\Domain\Entities\Product;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\UuidInterface;
use App\Models\Product as EloquentProduct;

interface ProductRepositoryInterface
{
    public function createProduct(Product $product): void;
    public function saveProduct(Product $product): void;
    public function updateProduct(Product $product): void;
    public function getAllProductsWithInstallments(): Collection;
    public function getProductById(UuidInterface $id): EloquentProduct|null;
    public function deleteProduct(UuidInterface $id): void;
}
