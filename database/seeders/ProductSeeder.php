<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Change the product name and price 
     * Run in command line "php artisan db:seed --class=ProductSeeder"
     * @return void
     */
    public function run()
    {
        Product::insert([
            'name' => 'Pensil',
            'price' => 5000
        ]);
    }
}
