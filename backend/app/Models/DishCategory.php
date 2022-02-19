<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\DishCategory
 *
 * @property int $id
 * @property int $dish_id
 * @property int $category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DishCategory newModelQuery()
 * @method static Builder|DishCategory newQuery()
 * @method static Builder|DishCategory query()
 * @method static Builder|DishCategory whereCategoryId(int $value)
 * @method static Builder|DishCategory whereCreatedAt(Carbon $value)
 * @method static Builder|DishCategory whereDishId(int $value)
 * @method static Builder|DishCategory whereId(int $value)
 * @method static Builder|DishCategory whereUpdatedAt(Carbon $value)
 * @mixin Eloquent
 * @property-read Category $category
 * @property-read Dish $dish
 */
class DishCategory extends Pivot
{
    public $incrementing = true;

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
