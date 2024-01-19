<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\DishIngredient
 *
 * @property int $id
 * @property int $dish_id
 * @property int $ingredient_id
 * @property string $amount Количество ингредиента в расчёте на одну порцию
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Dish $dish
 * @property-read Ingredient $ingredient
 * @method static Builder|DishIngredient newModelQuery()
 * @method static Builder|DishIngredient newQuery()
 * @method static Builder|DishIngredient query()
 * @method static Builder|DishIngredient whereAmount(int $value)
 * @method static Builder|DishIngredient whereCreatedAt(Carbon $value)
 * @method static Builder|DishIngredient whereDishId(int $value)
 * @method static Builder|DishIngredient whereId(int $value)
 * @method static Builder|DishIngredient whereIngredientId(int $value)
 * @method static Builder|DishIngredient whereUpdatedAt(Carbon $value)
 * @mixin Eloquent
 */
class DishIngredient extends Pivot
{
    public $incrementing = true;

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
