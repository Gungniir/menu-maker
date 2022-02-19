<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\DishTool
 *
 * @property int $id
 * @property int $dish_id
 * @property int $tool_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Dish $dish
 * @property-read Tool $tool
 * @method static Builder|DishTool newModelQuery()
 * @method static Builder|DishTool newQuery()
 * @method static Builder|DishTool query()
 * @method static Builder|DishTool whereCreatedAt(Carbon $value)
 * @method static Builder|DishTool whereDishId(int $value)
 * @method static Builder|DishTool whereId(int $value)
 * @method static Builder|DishTool whereToolId(int $value)
 * @method static Builder|DishTool whereUpdatedAt(Carbon $value)
 * @mixin Eloquent
 */
class DishTool extends Pivot
{
    public $incrementing = true;

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }
}
