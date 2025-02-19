<?php

declare(strict_types=1);

namespace Tests\Feature\Functional;

use Tests\TestCase;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\ProductManagement\Application\Services\ApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product as EloquentProduct;
use DateTimeImmutable;
use DateTimeZone;
use Modules\Shared\Domain\ValueObjects\Currency;
use Modules\Shared\Domain\ValueObjects\Money;
use Modules\Shared\Domain\ValueObjects\PaymentInstallment;

class DateTimeStorageTest extends TestCase
{
    use RefreshDatabase;

    public function test_dates_are_stored_in_utc(): void
    {
        $timezone = 'Africa/Nairobi';
        $productDate = new DateTimeImmutable('2024-03-21T14:00:00', new DateTimeZone($timezone));

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

        $response = $this->post(route('products.store'), [
            'name' => 'Test Product',
            'type' => ProductType::STUDENT->value,
            'priceAmount' => 100.00,
            'priceCurrency' => 'PLN',
            'soldDate' => $productDate->format('Y-m-d\TH:i'),
            'user_timezone' => $timezone
        ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $product = EloquentProduct::latest()->first();

        $expectedUtcTime = $productDate->setTimezone(new DateTimeZone('UTC'));
        $storedTime = new DateTimeImmutable($product->sold_date, new DateTimeZone('UTC'));

        $this->assertEquals(
            $expectedUtcTime->format('Y-m-d H:i:s'),
            $storedTime->format('Y-m-d H:i:s'),
            'Date should be stored in UTC'
        );
    }

    public function test_dates_are_stored_as_utc_in_view(): void
    {
        $userTimezone = 'Europe/Warsaw';
        $utcDateTime = "2024-03-21 12:00:00"; // 12:00 UTC

        $product = EloquentProduct::factory()->create([
            'sold_date' => $utcDateTime
        ]);

        $response = $this->get(route('products.show', [
            'product' => $product->id,
            'user_timezone' => $userTimezone
        ]));

        $expectedUTCTime = new DateTimeImmutable($utcDateTime, new DateTimeZone('UTC'));

        $response->assertStatus(200);
        $response->assertSee($expectedUTCTime->format('Y-m-d H:i'));
    }
}
