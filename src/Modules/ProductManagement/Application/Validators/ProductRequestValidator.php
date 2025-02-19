<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Validators;

use Illuminate\Validation\Rule;
use Modules\ProductManagement\Application\DTOs\RequestProductDTO;
use Modules\Shared\Application\Validators\SimpleProductRequestValidatorAbstract;

class ProductRequestValidator extends SimpleProductRequestValidatorAbstract
{
    protected function getDTOClass(): string
    {
        return RequestProductDTO::class;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'soldDate' => ['required', 'date_format:Y-m-d\TH:i'],
            'user_timezone' => ['required', 'string', Rule::in(\DateTimeZone::listIdentifiers())],
        ] + parent::rules();
    }

    protected function getValueCasting(): array
    {
        return ['soldDate' => 'date_iso8601'] + parent::getValueCasting();
    }

    protected function messages(): array
    {
        return [
            'soldDate.date_format' => 'Date format does not match Y-m-d\TH:i'
        ] + parent::messages();
    }
}
