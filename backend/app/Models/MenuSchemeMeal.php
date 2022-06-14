<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MenuSchemeMeal
 *
 * @property int $id
 * @property int $scheme_id
 * @property string $name
 * @property-read Collection|MenuSchemeRepeatItem[] $items
 * @property-read int|null $items_count
 * @property-read MenuScheme $scheme
 * @method static Builder|MenuSchemeMeal whereId(int $value)
 * @method static Builder|MenuSchemeMeal whereName(string $value)
 * @method static Builder|MenuSchemeMeal whereSchemeId(int $value)
 * @method static Builder|MenuSchemeMeal newModelQuery()
 * @method static Builder|MenuSchemeMeal newQuery()
 * @method static Builder|MenuSchemeMeal query()
 * @mixin Eloquent
 */
class MenuSchemeMeal extends Model
{
    public $timestamps = false;

    public function items(): HasMany
    {
        return $this->hasMany(MenuSchemeRepeatItem::class, 'meal_id');
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(MenuScheme::class, 'scheme_id');
    }
}
