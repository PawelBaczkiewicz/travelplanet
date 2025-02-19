<?php

declare(strict_types=1);

namespace Tests\Feature\Functional;

use Tests\TestCase;
use Modules\ProductManagement\Application\Services\ApiService;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\Shared\Domain\ValueObjects\Currency;
use Modules\Shared\Domain\ValueObjects\Money;
use Modules\ProductManagement\Domain\Entities\Product;
use Illuminate\Support\Facades\Config;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
use Modules\ProductManagement\Infrastructure\Api\ApiClient;
use Database\Seeders\UserSeeder;

class ApiConnectionTest extends TestCase
{
    private ApiService $apiService;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
        $this->seed(UserSeeder::class);

        $this->product = new Product(
            id: Uuid::uuid4(),
            name: 'Test Product',
            type: ProductType::STUDENT,
            price: new Money(100, Currency::PLN),
            soldDate: new DateTimeImmutable()
        );

        $apiClient = new ApiClient(new Client());
        $this->apiService = new ApiService($apiClient);
    }

    public function test_can_connect_to_api_with_valid_credentials(): void
    {
        $installments = $this->apiService->fetchPaymentInstallmentsFromApi($this->product);

        $this->assertNotEmpty($installments, 'API should return installments');
        $this->assertIsArray($installments);

        $firstInstallment = $installments[0];
        $this->assertInstanceOf(Money::class, $firstInstallment->getPrice());
        $this->assertInstanceOf(DateTimeImmutable::class, $firstInstallment->getDueDate());
    }

    public function test_cannot_connect_to_api_with_invalid_credentials(): void
    {
        Config::set('services.travelplanet_api.username', 'unknown@travelplanet');

        $this->expectException(\GuzzleHttp\Exception\ClientException::class);
        $this->expectExceptionCode(401);

        $this->apiService->fetchPaymentInstallmentsFromApi($this->product);
    }

    public function test_handles_api_timeout(): void
    {
        Config::set('services.travelplanet_api.timeout', 0.001); // 1ms timeout

        $this->expectException(\GuzzleHttp\Exception\ConnectException::class);

        $this->apiService->fetchPaymentInstallmentsFromApi($this->product);
    }

    public function test_handles_api_credentials_wrong_role(): void
    {
        Config::set('services.travelplanet_api.username', 'restricted@travelplanet');

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(403);

        $this->apiService->fetchPaymentInstallmentsFromApi($this->product);
    }

    public function test_handles_different_api_versions_with_student_product(): void
    {
        Config::set('services.travelplanet_api.version', 'v2');
        $installmentsV2 = $this->apiService->fetchPaymentInstallmentsFromApi($this->product);

        $this->assertCount(3, $installmentsV2, 'V2 API should return 3 installments for students');
        $this->assertEquals(
            33,
            $installmentsV2[0]->getPrice()->getAmount(),
            'First installment should be 33% of total price'
        );


        Config::set('services.travelplanet_api.version', 'v1');
        $installmentsV1 = $this->apiService->fetchPaymentInstallmentsFromApi($this->product);

        $this->assertCount(1, $installmentsV1, 'V1 API should return 2 default installments');
        $this->assertEquals(
            100.00,
            $installmentsV1[0]->getPrice()->getAmount(),
            'First installment should be 100% of total price'
        );
    }

    public function test_handles_unsupported_api_version(): void
    {
        Config::set('services.travelplanet_api.version', 'v3');

        $this->expectException(\GuzzleHttp\Exception\ClientException::class);
        $this->expectExceptionCode(404);

        $this->apiService->fetchPaymentInstallmentsFromApi($this->product);
    }
}
