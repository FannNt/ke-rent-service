<?php

namespace Database\Seeders;

use App\Models\UsersStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UsersStatus::factory()->create([
            'role' => 'user',
        ]);
    }
}
