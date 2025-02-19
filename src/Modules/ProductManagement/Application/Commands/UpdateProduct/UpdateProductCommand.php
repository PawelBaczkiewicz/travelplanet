<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Commands\UpdateProduct;

use Modules\ProductManagement\Application\DTOs\RequestProductDTO;

final readonly class UpdateProductCommand
{
    public function __construct(
        public RequestProductDTO $requestProductDTO
    ) {}
}
