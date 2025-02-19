<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Commands\CreateProduct;

use Modules\ProductManagement\Domain\Repositories\ProductRepositoryInterface;
use Modules\ProductManagement\Application\Services\ApiService;

final readonly class CreateProductCommandHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ApiService $apiService
    ) {}

    public function handle(CreateProductCommand $command): void
    {
        $requestProductDTO = $command->requestProductDTO;
        $product = $requestProductDTO->toEntity();
        $installments = $this->apiService->fetchPaymentInstallmentsFromApi($product);
        $product->setPaymentInstallments($installments);
        $this->productRepository->createProduct($product);
    }
}
