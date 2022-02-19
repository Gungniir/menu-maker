<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\DishPreparation
 *
 * @property int $id
 * @property int $dish_id
 * @property int $preparation_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Dish $dish
 * @property-read Preparation $preparation
 * @method static Builder|DishPreparation newModelQuery()
 * @method static Builder|DishPreparation newQuery()
 * @method static Builder|DishPreparation query()
 * @method static Builder|DishPreparation whereCreatedAt(Carbon $value)
 * @method static Builder|DishPreparation whereDishId(int $value)
 * @method static Builder|DishPreparation whereId(int $value)
 * @method static Builder|DishPreparation wherePreparationId(int $value)
 * @method static Builder|DishPreparation whereUpdatedAt(Carbon $value)
 * @mixin Eloquent
 */
class DishPreparation extends Pivot
{
    public $incrementing = true;

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    public function preparation(): BelongsTo
    {
        return $this->belongsTo(Preparation::class);
    }
}
