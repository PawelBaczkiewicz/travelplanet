<?php

declare(strict_types=1);

namespace Tests\Feature\Functional;

use Tests\TestCase;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\ProductManagement\Application\Services\ApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use App\Models\Product as EloquentProduct;

class UpdateProductTest extends TestCase
{
    use RefreshDatabase;

    private string $productId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productId = Uuid::uuid4()->toString();
        $product = EloquentProduct::factory()->create([
            'id' => $this->productId,
            'type' => ProductType::STUDENT->value
        ]);
    }

    public function test_can_update_existing_product(): void
    {
        $this->mock(ApiService::class, function ($mock) {
            $mock->shouldReceive('fetchPaymentInstallmentsFromApi')
                ->once()
                ->andReturn([]);
        });

        $updateData = [
            'name' => 'Updated Product',
            'type' => ProductType::STANDARD->value,
            'priceAmount' => 200.00,
            'priceCurrency' => 'EUR',
            'soldDate' => '2024-03-21T12:00',
            'user_timezone' => 'Europe/Warsaw'
        ];

        $response = $this->put(
            route('products.update', ['product' => $this->productId]),
            $updateData
        );

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $this->productId,
            'name' => 'Updated Product',
            'type' => ProductType::STANDARD->value
        ]);
    }
}
