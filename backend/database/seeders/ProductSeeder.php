<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coffeeBeans = [
            [
                'name' => 'Arabica Coffee Beans',
                'description' => 'Premium Arabica coffee beans with a smooth, mild flavor and subtle sweetness. Perfect for those who enjoy a balanced cup of coffee with low acidity.',
                'price' => 24.99,
                'stock_quantity' => 50,
                'image' => 'arabica-beans.jpg',
                'status' => 'active',
            ],
            [
                'name' => 'Robusta Coffee Beans',
                'description' => 'Strong and bold Robusta beans with a rich, full-bodied taste and higher caffeine content. Ideal for espresso and those who prefer a more intense coffee experience.',
                'price' => 19.99,
                'stock_quantity' => 75,
                'image' => 'robusta-beans.jpg',
                'status' => 'active',
            ],
            [
                'name' => 'Ethiopian Yirgacheffe',
                'description' => 'Single-origin Ethiopian Yirgacheffe beans known for their bright, fruity notes and floral aroma. A specialty coffee with complex flavors of citrus and jasmine.',
                'price' => 32.99,
                'stock_quantity' => 30,
                'image' => 'yirgacheffe-beans.jpg',
                'status' => 'active',
            ],
            [
                'name' => 'Colombian Supremo',
                'description' => 'Premium Colombian Supremo beans with a well-balanced flavor profile, medium body, and nutty undertones. A classic choice for coffee enthusiasts.',
                'price' => 27.99,
                'stock_quantity' => 45,
                'image' => 'colombian-supremo-beans.jpg',
                'status' => 'active',
            ],
        ];

        foreach ($coffeeBeans as $bean) {
            Product::create($bean);
        }
    }
}
