<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObjects;

enum RoleEnum: string
{
    case ADMIN = "admin";
    case EDITOR = "editor";
    case RESTRICTED = "restricted";
}
