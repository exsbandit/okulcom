<?php


namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create(
            [
                'id' => 100,
                'name' => "Product-1",
                "category_id" => 1,
                'price' => 120.75,
                'stock' => 10,
            ]
        );
        Product::create(
            [
                'id' => 101,
                'name' => "Product-2",
                "category_id" => 1,
                'price' => 49.50,
                'stock' => 10,
            ]
        );
        Product::create(
            [
                'id' => 102,
                'name' => "Product-3",
                "category_id" => 2,
                'price' => 11.28,
                'stock' => 10,
            ]
        );
        Product::create(
            [
                'id' => 103,
                'name' => "Product-4",
                "category_id" => 2,
                'price' => 22.80,
                'stock' => 10,
            ]
        );
        Product::create(
            [
                'id' => 104,
                'name' => "Product-5",
                "category_id" => 2,
                'price' => 12.95,
                'stock' => 10,
            ]
        );
    }
}
