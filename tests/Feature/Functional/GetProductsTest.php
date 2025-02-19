<?php

declare(strict_types=1);

namespace Tests\Feature\Functional;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product as EloquentProduct;

class GetProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products(): void
    {
        EloquentProduct::factory()->count(3)->create();
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertViewHas('products');
        $response->assertSee('All Products');
    }

    public function test_can_view_single_product(): void
    {
        $product = EloquentProduct::factory()->create([
            'name' => 'Test Product View'
        ]);

        $response = $this->get(route('products.show', ['product' => $product->id]));

        $response->assertStatus(200);
        $response->assertSee('Test Product View');
    }
}
