<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (User::get() as $user) {
            Tool::updateOrCreate([
                'creator_id' => $user->id,
                'name' => 'Сковородка'
            ], [
                'amount' => 2,
                'reusable' => false
            ]);

            Tool::updateOrCreate([
                'creator_id' => $user->id,
                'name' => 'Кастрюля'
            ], [
                'amount' => 2,
                'reusable' => false
            ]);

            Tool::updateOrCreate([
                'creator_id' => $user->id,
                'name' => 'Миксер'
            ], [
                'amount' => 1,
                'reusable' => true
            ]);

            Tool::updateOrCreate([
                'creator_id' => $user->id,
                'name' => 'Толкушка'
            ], [
                'amount' => 1,
                'reusable' => true
            ]);

            Tool::updateOrCreate([
                'creator_id' => $user->id,
                'name' => 'Блендер'
            ], [
                'amount' => 1,
                'reusable' => true
            ]);

            Tool::updateOrCreate([
                'creator_id' => $user->id,
                'name' => 'Нож филейный'
            ], [
                'amount' => 1,
                'reusable' => true
            ]);

            Tool::updateOrCreate([
                'creator_id' => $user->id,
                'name' => 'Нож для стейков'
            ], [
                'amount' => 1,
                'reusable' => true
            ]);

            Tool::updateOrCreate([
                'creator_id' => $user->id,
                'name' => 'Нож для хлеба'
            ], [
                'amount' => 1,
                'reusable' => true
            ]);

            Tool::updateOrCreate([
                'creator_id' => $user->id,
                'name' => 'Нож для фруктов'
            ], [
                'amount' => 1,
                'reusable' => true
            ]);

            Tool::updateOrCreate([
                'creator_id' => $user->id,
                'name' => 'Шеф нож'
            ], [
                'amount' => 1,
                'reusable' => true
            ]);
        }
    }
}
