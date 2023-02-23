<?php

namespace Database\Seeders;

use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        BrandFactory::new()->count(20)->create();

        Categoryfactory::new()->count(10)
            ->has(Product::factory(rand(5,15)))
            ->create();
    }
}
