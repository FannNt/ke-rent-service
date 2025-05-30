<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UsersStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UsersStatus::factory(10)->create()->each(function ($user){
            User::factory()->create([
                'user_status_id' => $user->id,
            ]);
        });
    }
}
