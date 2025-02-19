<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Application\Validators;

use Modules\Shared\Application\Validators\SimpleProductRequestValidatorAbstract;
use Modules\Shared\Application\DTOs\RequestProductInstallmentsDTO;

class ProductRequestValidator extends SimpleProductRequestValidatorAbstract
{
    protected function getDTOClass(): string
    {
        return RequestProductInstallmentsDTO::class;
    }
}
