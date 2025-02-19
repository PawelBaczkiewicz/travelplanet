<?php

declare(strict_types=1);

namespace Modules\Shared\Application\Validators;

use Illuminate\Validation\Rule;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\Shared\Domain\ValueObjects\Currency;

abstract class SimpleProductRequestValidatorAbstract extends ProductRequestValidatorAbstract
{
    abstract protected function getDTOClass(): string;

    protected function getValueCasting(): array
    {
        return [
            'soldDate' => 'date_iso8601',
            'priceAmount' => 'float'
        ];
    }

    protected function rules(): array
    {
        $rules = [
            'type' => ['required', 'string', Rule::enum(ProductType::class)],
            'priceAmount' => ['required', 'numeric', 'min:1'],
            'priceCurrency' => ['required', 'string', Rule::enum(Currency::class)],
            'soldDate' => ['required', 'date_format:Y-m-d\TH:i:sP'],
        ];

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'type' => 'Product type is invalid. Possible values: [' . implode(', ', ProductType::values()) . ']',
            'soldDate.date_format' => 'Date format does not match ISO 8601'
        ];
    }
}
