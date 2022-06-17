<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\MenuItem
 *
 * @property int $id
 * @property int $meal_id
 * @property int $dish_id
 * @property int $day 0 - пн, 6 - вс
 * @property-read MenuMeal $meal
 * @method static Builder|MenuItem whereDay(int $value)
 * @method static Builder|MenuItem whereDishId(int $value)
 * @method static Builder|MenuItem whereId(int $value)
 * @method static Builder|MenuItem whereMealId(int $value)
 * @method static Builder|MenuItem newModelQuery()
 * @method static Builder|MenuItem newQuery()
 * @method static Builder|MenuItem query()
 * @mixin Eloquent
 */
class MenuItem extends Model
{
    public $timestamps = false;

    public function meal(): BelongsTo
    {
        return $this->belongsTo(MenuMeal::class, 'meal_id');
    }

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }
}
