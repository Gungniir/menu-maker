<?php

namespace Database\Factories;

use App\Enums\IngredientUnit;
use App\Models\Ingredient;
use App\Models\User;
use Faker\Provider\ru_RU\Text;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory<Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['creator_id' => "\Database\Factories\UserFactory", 'name' => "string", 'is_perishable' => "bool", 'amount' => "int", 'unit' => "\App\Enums\IngredientUnit"])]
    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'name' => Text::regexify(/** @lang RegExp */ '/[\w ]{10,20}/'),
            'is_perishable' => Text::numberBetween(0, 1) === 0,
            'amount' => Text::numberBetween(0, 15),
            'unit' => IngredientUnit::cases()[Text::numberBetween(0, count(IngredientUnit::cases()) - 1)],
        ];
    }
}
