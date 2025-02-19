<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Modules\PaymentScheduleAPI\Domain\Services\PaymentScheduleCalculatorInterface;
use Modules\PaymentScheduleAPI\Application\DTOs\ResponsePaymentScheduleDTO;
use Illuminate\Http\JsonResponse;
use Modules\PaymentScheduleAPI\Infrastructure\Services\ApiVersionService;
use Modules\Shared\Application\Validators\ValidationException;
use Modules\PaymentScheduleAPI\Application\Validators\ProductRequestValidator;
use Modules\Shared\Infrastructure\Facades\Log;

class PaymentScheduleController
{
    public function __construct(
        private readonly PaymentScheduleCalculatorInterface $calculator,
        private readonly ProductRequestValidator $validator,
        private readonly ApiVersionService $apiVersionService
    ) {}

    public function calculate(Request $request): JsonResponse
    {
        Log::logDebug('Payment schedule calculation started');

        try {
            $requestProductInstallmentsDTO = $this->validator->validate()->getValidatedDTO();

            $paymentScheduleResponseDTO = ResponsePaymentScheduleDTO::success(
                apiVersion: $this->apiVersionService->getApiVersion(),
                data: $this->calculator->calculate($requestProductInstallmentsDTO),
            );

            return response()->json($paymentScheduleResponseDTO, 200);
        } catch (ValidationException $e) {

            $paymentScheduleResponseDTO = ResponsePaymentScheduleDTO::error(
                apiVersion: $this->apiVersionService->getApiVersion(),
                errorCode: $e->getCode(),
                errorMessage: $e->getMessage(),
                errorDetails: $e->getErrors()
            );

            return response()->json($paymentScheduleResponseDTO, $e->getCode());
        }
    }
}
