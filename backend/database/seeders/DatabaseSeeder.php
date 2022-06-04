<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (User::whereEmail('support@menumaker.ru')->count() === 0) {
            User::factory()->state([
                'email' => 'support@menumaker.ru',
                'password' => Hash::make('password'),
            ])->create();
        }

        (new DishSeeder())->run();
    }
}
