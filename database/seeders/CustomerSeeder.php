<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample customers
        Customer::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'address' => '123 Main St, Anytown, USA',
        ]);

        Customer::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '+0987654321',
            'address' => '456 Elm St, Somewhere, USA',
        ]);

        // Create additional customers using factory
        Customer::factory(10)->create();
    }
}
