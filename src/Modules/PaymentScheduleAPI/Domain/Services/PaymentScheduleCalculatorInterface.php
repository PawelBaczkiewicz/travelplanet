<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Domain\Services;

use Modules\Shared\Application\DTOs\RequestProductInstallmentsDTO;

interface PaymentScheduleCalculatorInterface
{
    public function calculate(RequestProductInstallmentsDTO $requestProductInstallmentsDTO): array;
}
