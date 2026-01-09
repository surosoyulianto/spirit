<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Kaos Polos Hitam',
                'description' => 'Kaos cotton combed 24s warna hitam',
                'sku' => 'KPH-001',
                'price' => 50000,
                'stock' => 100,
                'category' => 'Kaos',
                'min_stock_level' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Kaos Polos Putih',
                'description' => 'Kaos cotton combed 24s warna putih',
                'sku' => 'KPH-002',
                'price' => 50000,
                'stock' => 150,
                'category' => 'Kaos',
                'min_stock_level' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Celana Pendek Denim',
                'description' => 'Celana pendek jeans casual',
                'sku' => 'CPD-001',
                'price' => 120000,
                'stock' => 50,
                'category' => 'Celana',
                'min_stock_level' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Kemeja Putih',
                'description' => 'Kemeja kantoran lengan panjang',
                'sku' => 'KMP-001',
                'price' => 150000,
                'stock' => 30,
                'category' => 'Kemeja',
                'min_stock_level' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Jaket Hoodie',
                'description' => 'Jaket hoodie unisex',
                'sku' => 'JHT-001',
                'price' => 200000,
                'stock' => 25,
                'category' => 'Jaket',
                'min_stock_level' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Rok Plisket',
                'description' => 'Rok plisket wanita',
                'sku' => 'RKP-001',
                'price' => 75000,
                'stock' => 40,
                'category' => 'Rok',
                'min_stock_level' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Sweater Rajut',
                'description' => 'Sweater rajut wol',
                'sku' => 'SWR-001',
                'price' => 180000,
                'stock' => 20,
                'category' => 'Sweater',
                'min_stock_level' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Topi Baseball',
                'description' => 'Topi baseball cotton',
                'sku' => 'TPB-001',
                'price' => 35000,
                'stock' => 60,
                'category' => 'Aksesoris',
                'min_stock_level' => 15,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

