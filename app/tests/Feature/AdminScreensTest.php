<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminScreensTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_seeder_creates_demo_data(): void
    {
        $this->assertGreaterThan(0, Product::count());
        $this->assertGreaterThan(0, Customer::count());
        $this->assertGreaterThan(0, Order::count());
        $this->assertGreaterThan(0, (float) Order::sum('total'));
    }

    public function test_admin_screens_render(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $product = Product::first();
        $customer = Customer::first();
        $order = Order::first();

        $routes = [
            route('platform.main'),
            route('platform.products'),
            route('platform.products.create'),
            route('platform.products.edit', $product),
            route('platform.categories'),
            route('platform.customers'),
            route('platform.customers.edit', $customer),
            route('platform.orders'),
            route('platform.orders.view', $order),
            route('platform.systems.users'),
        ];

        foreach ($routes as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('platform.products'))->assertRedirect();
    }

    public function test_admin_can_update_product_and_order_status(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $product = Product::first();
        $order = Order::first();

        $this->actingAs($admin)
            ->post(route('platform.products.edit', [$product, 'method' => 'save']), [
                'product' => [
                    'name'        => 'Bijgewerkt product',
                    'sku'         => $product->sku,
                    'category_id' => $product->category_id,
                    'price'       => 12.50,
                    'stock'       => 7,
                    'description' => 'Test',
                    'is_active'   => 1,
                ],
            ])->assertRedirect(route('platform.products'));

        $this->assertSame('Bijgewerkt product', $product->fresh()->name);

        $this->actingAs($admin)
            ->post(route('platform.orders.view', [$order, 'method' => 'updateStatus']), [
                'order' => ['status' => 'shipped', 'notes' => 'Verzonden via PostNL'],
            ])->assertRedirect();

        $this->assertSame('shipped', $order->fresh()->status);
    }
}
