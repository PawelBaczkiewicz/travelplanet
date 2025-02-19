<?php

declare(strict_types=1);

namespace Modules\Shared\Application\DTOs;

use Modules\Shared\Domain\Entities\ProductInterface;

interface RequestProductDTOInterface
{
    public static function createFromArray(array $data): static;

    public function toEntity(): ProductInterface;

    public function toArray(): array;
}
