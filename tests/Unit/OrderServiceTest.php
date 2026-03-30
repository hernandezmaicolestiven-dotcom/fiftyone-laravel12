<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas unitarias para OrderService.
 * Cubre creación de pedidos y actualización de estado.
 */
class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OrderService::class);
    }

    /** @test */
    public function it_creates_an_order_with_correct_total(): void
    {
        $product = Product::factory()->create(['price' => 50000, 'stock' => 10]);

        $order = $this->service->createOrder([
            'customer_name' => 'Juan Test',
            'items'         => [['product_id' => $product->id, 'quantity' => 2]],
        ]);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(100000, $order->total);
        $this->assertEquals('pending', $order->status);
        $this->assertCount(1, $order->items);
    }

    /** @test */
    public function it_creates_order_items_with_correct_subtotals(): void
    {
        $product = Product::factory()->create(['price' => 30000]);

        $order = $this->service->createOrder([
            'customer_name' => 'Ana Test',
            'items'         => [['product_id' => $product->id, 'quantity' => 3]],
        ]);

        $item = $order->items->first();
        $this->assertEquals(90000, $item->subtotal);
        $this->assertEquals(3, $item->quantity);
        $this->assertEquals($product->name, $item->product_name);
    }

    /** @test */
    public function it_updates_order_status(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $updated = $this->service->updateStatus($order, 'confirmed');

        $this->assertEquals('confirmed', $updated->status);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'confirmed']);
    }

    /** @test */
    public function it_stores_customer_email_when_provided(): void
    {
        $product = Product::factory()->create(['price' => 10000]);

        $order = $this->service->createOrder([
            'customer_name'  => 'María Test',
            'customer_email' => 'maria@test.com',
            'items'          => [['product_id' => $product->id, 'quantity' => 1]],
        ]);

        $this->assertEquals('maria@test.com', $order->customer_email);
    }
}
