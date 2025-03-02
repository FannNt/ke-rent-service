<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaction')->insert([
            [
                'payment_id' => 1,
                'user_id' => 1,
                'total_price' => number_format("30000", 2, ",", "."),
                'status' => 'pending',
                'create_at' => Carbon::now()
            ],
            [
                'payment_id' => 2,
                'user_id' => 2,
                'total_price' => number_format("50000", 2, ",", "."),
                'status' => 'approved',
                'create_at' => Carbon::now()
            ]
        ]);
    }
}
