<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\DishImage
 *
 * @property int $id
 * @property int $dish_id
 * @property int $image_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DishImage newModelQuery()
 * @method static Builder|DishImage newQuery()
 * @method static Builder|DishImage query()
 * @method static Builder|DishImage whereCreatedAt(Carbon $value)
 * @method static Builder|DishImage whereDishId(int $value)
 * @method static Builder|DishImage whereId(int $value)
 * @method static Builder|DishImage whereImageId(int $value)
 * @method static Builder|DishImage whereUpdatedAt(Carbon $value)
 * @mixin Eloquent
 * @property-read Dish $dish
 * @property-read Image $image
 */
class DishImage extends Pivot
{
    public $incrementing = true;

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
