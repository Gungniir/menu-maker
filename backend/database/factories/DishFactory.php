<?php

namespace Database\Factories;

use App\Models\Dish;
use App\Models\User;
use Faker\Provider\ru_RU\Text;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dish>
 */
class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'name' => Text::regexify(/** @lang RegExp */ '/\w{100,255}/'),
            'cooking_time' => Text::numberBetween(0, 60*24),
            'kcal' => Text::numberBetween(0, 32767),
            'proteins' => Text::numberBetween(0, 32767),
            'fats' => Text::numberBetween(0, 32767),
            'carbohydrates' => Text::numberBetween(0, 32767),
            'link' => Text::regexify(/** @lang RegExp */ '/\w{1000}/'),
        ];
    }
}
