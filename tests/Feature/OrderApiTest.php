<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de integración para el endpoint público de pedidos.
 */
class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_order_via_api(): void
    {
        $product = Product::factory()->create(['price' => 25000, 'stock' => 5]);

        $response = $this->postJson(route('orders.store'), [
            'customer_name' => 'Carlos Test',
            'items'         => [['product_id' => $product->id, 'quantity' => 2]],
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'order_id'])
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Carlos Test',
            'total'         => 50000,
            'status'        => 'pending',
        ]);
    }

    /** @test */
    public function it_rejects_order_without_customer_name(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson(route('orders.store'), [
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['customer_name']);
    }

    /** @test */
    public function it_rejects_order_with_invalid_product(): void
    {
        $response = $this->postJson(route('orders.store'), [
            'customer_name' => 'Test',
            'items'         => [['product_id' => 9999, 'quantity' => 1]],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['items.0.product_id']);
    }

    /** @test */
    public function it_rejects_empty_items_array(): void
    {
        $response = $this->postJson(route('orders.store'), [
            'customer_name' => 'Test',
            'items'         => [],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['items']);
    }
}
