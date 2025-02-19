<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Commands\DeleteProduct;

use Modules\ProductManagement\Domain\Repositories\ProductRepositoryInterface;
use Modules\ProductManagement\Application\Services\ApiService;

final readonly class DeleteProductCommandHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function handle(DeleteProductCommand $command): void
    {
        $this->productRepository->deleteProduct($command->id);
    }
}
