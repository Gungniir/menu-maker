<?php

namespace App\Models;

use Database\Factories\RecipeItemFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\RecipeItem
 *
 * @property-read Dish|null $dish
 * @method static RecipeItemFactory factory(...$parameters)
 * @method static Builder|RecipeItem newModelQuery()
 * @method static Builder|RecipeItem newQuery()
 * @method static Builder|RecipeItem query()
 * @mixin Eloquent
 * @property int $id
 * @property int $dish_id Блюдо
 * @property string $item Сам элемент рецепта
 * @property int $order Порядок элементов рецепта
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|RecipeItem whereCreatedAt(Carbon $value)
 * @method static Builder|RecipeItem whereDeletedAt(Carbon $value)
 * @method static Builder|RecipeItem whereDishId(int $value)
 * @method static Builder|RecipeItem whereId(int $value)
 * @method static Builder|RecipeItem whereItem(string $value)
 * @method static Builder|RecipeItem whereOrder(int $value)
 * @method static Builder|RecipeItem whereUpdatedAt(Carbon $value)
 */
class RecipeItem extends Model
{
    use HasFactory;

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }
}
