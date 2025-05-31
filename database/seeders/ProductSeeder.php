<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                "name" => "laptop",
                "price" => 1000,
                "category" => "Technologi",
                "product_condition" => "Good",
                "description" => "laptopasdasd",
                "user_id" => 1,
                "image_publicId" => "asdasdasd"
            ],
        ]);
    }
}
