<?php

declare(strict_types=1);

namespace Tests\Feature\Functional;

use Tests\TestCase;

class RoutingTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/products');
    }
}
