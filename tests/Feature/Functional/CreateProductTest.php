<?php

declare(strict_types=1);

namespace Tests\Feature\Functional;

use Tests\TestCase;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\ProductManagement\Application\Services\ApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Shared\Domain\ValueObjects\Currency;
use Modules\Shared\Domain\ValueObjects\Money;
use Modules\Shared\Domain\ValueObjects\PaymentInstallment;
use DateTimeImmutable;

class CreateProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product_with_valid_data(): void
    {
        $productDate = new DateTimeImmutable("2025-06-30 12:00:00+05:00");

        $this->mock(ApiService::class, function ($mock) use ($productDate) {
            $mock->shouldReceive('fetchPaymentInstallmentsFromApi')
                ->once()
                ->andReturn([
                    new PaymentInstallment(
                        price: new Money(100, Currency::PLN),
                        dueDate: $productDate
                    )
                ]);
        });

        $productData = [
            'name' => 'Test Product',
            'type' => ProductType::STUDENT->value,
            'priceAmount' => 100.00,
            'priceCurrency' => 'PLN',
            'soldDate' => $productDate->format('Y-m-d\TH:i'),
            'user_timezone' => 'Europe/Warsaw'
        ];

        $response = $this->post(route('products.store'), $productData);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'type' => ProductType::STUDENT->value,
            'price_amount' => 100.00,
            'price_currency' => 'PLN'
        ]);
    }

    public function test_cannot_create_product_with_invalid_data(): void
    {
        $response = $this->post(route('products.store'), [
            'name' => '',
            'type' => 'invalid_type',
            'priceAmount' => -100,
            'priceCurrency' => 'INVALID',
            'soldDate' => 'not-a-date',
            'user_timezone' => 'Europe/Warsaw'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'type', 'priceAmount', 'priceCurrency', 'soldDate']);
    }
}
