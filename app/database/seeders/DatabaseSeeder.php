<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data.
     */
    public function run(): void
    {
        // Admin user for the Orchid dashboard (/admin)
        User::createAdmin('Admin', 'admin@example.com', 'password');

        $categories = collect([
            ['name' => 'Laptops', 'description' => 'Notebooks en ultrabooks'],
            ['name' => 'Smartphones', 'description' => 'Telefoons van alle merken'],
            ['name' => 'Audio', 'description' => 'Koptelefoons, speakers en soundbars'],
            ['name' => 'Accessoires', 'description' => 'Kabels, hoesjes en opladers'],
            ['name' => 'Gaming', 'description' => 'Consoles, controllers en games'],
            ['name' => 'Smart Home', 'description' => 'Slimme verlichting en thermostaten'],
        ])->map(fn (array $c) => Category::factory()->create($c));

        $products = $categories->flatMap(
            fn (Category $category) => Product::factory()->count(8)->create(['category_id' => $category->id])
        );

        $customers = Customer::factory()->count(40)->create();

        Order::factory()->count(120)->make()->each(function (Order $order) use ($customers, $products) {
            $order->customer_id = $customers->random()->id;
            $order->save();

            $products->random(rand(1, 4))->each(function (Product $product) use ($order) {
                OrderItem::factory()->create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'unit_price' => $product->price,
                ]);
            });

            $order->recalculateTotal();
        });
    }
}
