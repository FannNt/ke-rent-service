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
                'user_id' => 1,
                'total_price' => 30000,
                'status' => 'pending',
                'created_at' => Carbon::now()
            ],
            [
                'user_id' => 2,
                'total_price' => 50000,
                'status' => 'approved',
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
