<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;


class PrdouctSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert([
            'name' => 'Book',
            'price' => 5000
        ]);
    }
}
