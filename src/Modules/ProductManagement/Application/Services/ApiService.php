<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Application\Services;

use DateTimeImmutable;
use Modules\ProductManagement\Domain\Entities\Product;
use Modules\ProductManagement\Infrastructure\Api\ApiClient;
use Modules\Shared\Domain\ValueObjects\PaymentInstallment;
use Modules\Shared\Domain\ValueObjects\Money;
use Modules\Shared\Application\DTOs\RequestProductInstallmentsDTO;
use Modules\Shared\Domain\ValueObjects\Currency;

class ApiService
{
    public function __construct(
        private readonly ApiClient $apiClient
    ) {}

    public function fetchPaymentInstallmentsFromApi(Product $product): array
    {
        $paymentInstallments = [];

        $requestProductInstallmentsDTO = new RequestProductInstallmentsDTO(
            type: $product->type->value,
            priceAmount: $product->price->getAmount(),
            priceCurrency: $product->price->getCurrency()->toString(),
            soldDate: $product->getSoldDateIso8601(),
        );

        $paymentInstallmentsApi = $this->apiClient->fetchProductData($requestProductInstallmentsDTO);

        foreach ($paymentInstallmentsApi as $installmentApi) {
            $paymentInstallments[] = new PaymentInstallment(
                price: new Money($installmentApi['amount'], Currency::from($installmentApi['currency'])),
                dueDate: new DateTimeImmutable($installmentApi['dueDate'])
            );
        }

        return $paymentInstallments;
    }
}
