<?php

namespace Database\Seeders;

use App\Enums\IngredientUnit;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (User::get() as $user) {
            Ingredient::updateOrInsert([
                'creator_id' => $user->id,
                'name' => 'Молоко'
            ], [
                'is_perishable' => true,
                'amount' => 1000,
                'unit' => IngredientUnit::Milliliters,
            ]);

            Ingredient::updateOrInsert([
                'creator_id' => $user->id,
                'name' => 'Сосиски'
            ], [
                'is_perishable' => true,
                'amount' => 500,
                'unit' => IngredientUnit::Grams,
            ]);

            Ingredient::updateOrInsert([
                'creator_id' => $user->id,
                'name' => 'Фисташки'
            ], [
                'is_perishable' => false,
                'amount' => 150,
                'unit' => IngredientUnit::Grams,
            ]);

            Ingredient::updateOrInsert([
                'creator_id' => $user->id,
                'name' => 'Сахар'
            ], [
                'is_perishable' => false,
                'amount' => 2000,
                'unit' => IngredientUnit::Grams,
            ]);

            Ingredient::updateOrInsert([
                'creator_id' => $user->id,
                'name' => 'Долька чеснока'
            ], [
                'is_perishable' => false,
                'amount' => 5,
                'unit' => IngredientUnit::Pieces,
            ]);
        }
    }
}
