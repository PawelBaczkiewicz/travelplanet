<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Commands\UpdateProduct;

use Modules\ProductManagement\Domain\Repositories\ProductRepositoryInterface;
use Modules\ProductManagement\Application\Services\ApiService;

final readonly class UpdateProductCommandHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ApiService $apiService
    ) {}

    public function handle(UpdateProductCommand $command): void
    {
        $requestProductDTO = $command->requestProductDTO;
        $eloquentProduct = $this->productRepository->getProductById($requestProductDTO->id);
        if (!$eloquentProduct) {
            throw new \InvalidArgumentException("Product[{$requestProductDTO->id}] not found");
        }

        $product = $requestProductDTO->toEntity();
        $installments = $this->apiService->fetchPaymentInstallmentsFromApi($product);
        $product->setPaymentInstallments($installments);
        $this->productRepository->updateProduct($product);
    }
}
