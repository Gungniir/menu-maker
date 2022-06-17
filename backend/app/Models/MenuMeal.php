<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MenuMeal
 *
 * @property int $id
 * @property int $menu_id
 * @property string $name
 * @property-read Collection|MenuItem[] $items
 * @property-read int|null $items_count
 * @property-read Menu $menu
 * @method static Builder|MenuMeal whereId(int $value)
 * @method static Builder|MenuMeal whereMenuId(int $value)
 * @method static Builder|MenuMeal whereName(string $value)
 * @method static Builder|MenuMeal newModelQuery()
 * @method static Builder|MenuMeal newQuery()
 * @method static Builder|MenuMeal query()
 * @mixin Eloquent
 */
class MenuMeal extends Model
{
    public $timestamps = false;

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'meal_id');
    }
}
