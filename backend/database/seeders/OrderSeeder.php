<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Please run CustomerSeeder and ProductSeeder first!');
            return;
        }

        // Order 1: Customer 1 - Single product order
        $order1 = Order::create([
            'customer_id' => $customers[0]->id,
            'status' => 'completed',
            'total_amount' => 0, // Will be calculated
        ]);

        $product1 = $products[0];
        $quantity1 = 2;
        $price1 = $product1->price;
        $subtotal1 = $quantity1 * $price1;

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $product1->id,
            'quantity' => $quantity1,
            'price' => $price1,
            'subtotal' => $subtotal1,
        ]);

        $order1->update(['total_amount' => $subtotal1]);

        // Order 2: Customer 2 - Multiple products order
        $order2 = Order::create([
            'customer_id' => $customers[1]->id,
            'status' => 'completed',
            'total_amount' => 0,
        ]);

        $product2a = $products[1];
        $product2b = $products[2];
        
        $item2a = OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $product2a->id,
            'quantity' => 3,
            'price' => $product2a->price,
            'subtotal' => 3 * $product2a->price,
        ]);

        $item2b = OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $product2b->id,
            'quantity' => 1,
            'price' => $product2b->price,
            'subtotal' => 1 * $product2b->price,
        ]);

        $order2->update(['total_amount' => $item2a->subtotal + $item2b->subtotal]);

        // Order 3: Customer 3 - Pending order
        $order3 = Order::create([
            'customer_id' => $customers[2]->id,
            'status' => 'pending',
            'total_amount' => 0,
        ]);

        $product3 = $products[3];
        $item3 = OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $product3->id,
            'quantity' => 1,
            'price' => $product3->price,
            'subtotal' => 1 * $product3->price,
        ]);

        $order3->update(['total_amount' => $item3->subtotal]);

    }
}
