<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Queries\GetProduct;

use Ramsey\Uuid\UuidInterface;

final readonly class GetProductQuery
{
    public function __construct(
        public UuidInterface $id
    ) {}
}
