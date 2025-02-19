<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObjects;

enum ProductType: string
{
    case STANDARD = 'standard';
    case PREMIUM = 'premium';
    case STUDENT = 'student';
    case TRIAL = 'trial';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::STANDARD => 'Standard',
            self::PREMIUM => 'Premium',
            self::STUDENT => 'Student Package',
            self::TRIAL => 'Trial Version',
        };
    }
}
