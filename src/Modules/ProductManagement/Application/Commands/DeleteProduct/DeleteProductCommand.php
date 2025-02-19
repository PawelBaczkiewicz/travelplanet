<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Commands\DeleteProduct;

use Ramsey\Uuid\UuidInterface;

final readonly class DeleteProductCommand
{
    public function __construct(
        public UuidInterface $id
    ) {}
}
