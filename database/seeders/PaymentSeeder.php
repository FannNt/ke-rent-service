<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;  
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment')->insert([
            [
                "methods" => "Other",
                "status" => "paid",
                "created_at" => now()
            ],
            [
                "methods" => "COD",
                "status" => "not paid",
                "created_at" => now()
            ],
        ]);
    }
}
