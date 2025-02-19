<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\ProductManagement\Application\Services\ApiService;
use Modules\Shared\Domain\ValueObjects\Currency;
use Modules\Shared\Domain\ValueObjects\Money;
use Modules\Shared\Domain\ValueObjects\PaymentInstallment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use DateTimeImmutable;
use Modules\ProductManagement\Domain\Entities\Product;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
use Modules\ProductManagement\Infrastructure\Api\ApiClient;
use App\Models\Product as EloquentProduct;
use App\Models\PaymentInstallment as EloquentInstallment;
use Database\Seeders\UserSeeder;
use DateTimeZone;

class JuneProductTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
        $this->seed(UserSeeder::class);
    }
    public function test_can_create_and_view_june_rule_product(): void
    {
        $productName = 'June Special Product';
        $timezone = 'America/Los_Angeles';
        $juneDate = new DateTimeImmutable("2025-06-15 12:00:00", new DateTimeZone($timezone));

        $productData = [
            'name' => $productName,
            'type' => ProductType::STUDENT->value,
            'priceAmount' => 100.00,
            'priceCurrency' => 'PLN',
            'soldDate' => $juneDate->format('Y-m-d\TH:i'),
            'user_timezone' => $timezone
        ];

        $response = $this->post(route('products.store'), $productData);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $product = EloquentProduct::where('name', 'June Special Product')->first();
        $this->assertNotNull($product);

        $installments = EloquentInstallment::where('product_id', $product->id)
            ->orderBy('due_date')
            ->get();

        $this->assertCount(2, $installments);

        $this->assertEquals(30.00, $installments[0]->amount);
        $this->assertEquals('PLN', $installments[0]->currency);
        $this->assertEquals(
            $juneDate->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
            $installments[0]->due_date
        );

        $this->assertEquals(70.00, $installments[1]->amount);
        $this->assertEquals('PLN', $installments[1]->currency);
        $this->assertEquals(
            $juneDate->modify('last day of +3 months')->setTime(23, 59, 59)->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
            $installments[1]->due_date
        );

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee($productName);
        $response->assertSee('30.00');
        $response->assertSee('70.00');
    }
}
