<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObjects;

/**
 * Represents a currency defined by the ISO 4217 standard.
 * @see https://en.wikipedia.org/wiki/ISO_4217
 */
enum Currency: string
{
    case PLN = 'PLN';
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case CHF = 'CHF';
    case CAD = 'CAD';
    case AUD = 'AUD';
    case NZD = 'NZD';
    case JPY = 'JPY';
    case CNY = 'CNY';

    public function toString(): string
    {
        return $this->value;
    }
}
