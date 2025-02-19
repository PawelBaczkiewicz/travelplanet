<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Commands\CreateProduct;

use Modules\ProductManagement\Application\DTOs\RequestProductDTO;

final readonly class CreateProductCommand
{
    public function __construct(
        public RequestProductDTO $requestProductDTO
    ) {}
}
