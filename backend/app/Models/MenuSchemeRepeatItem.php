<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\MenuSchemeRepeatItem
 *
 * @property int $id
 * @property int $group_id
 * @property int $meal_id
 * @property int $day 0 - пн, ..., 6 - вс
 * @property-read MenuSchemeRepeatGroup $group
 * @property-read MenuSchemeMeal $meal
 * @method static Builder|MenuSchemeRepeatItem whereDay(int $value)
 * @method static Builder|MenuSchemeRepeatItem whereGroupId(int $value)
 * @method static Builder|MenuSchemeRepeatItem whereId(int $value)
 * @method static Builder|MenuSchemeRepeatItem whereMealId(int $value)
 * @method static Builder|MenuSchemeRepeatItem newModelQuery()
 * @method static Builder|MenuSchemeRepeatItem newQuery()
 * @method static Builder|MenuSchemeRepeatItem query()
 * @mixin Eloquent
 */
class MenuSchemeRepeatItem extends Model
{
    public $timestamps = false;

    public function meal(): BelongsTo
    {
        return $this->belongsTo(MenuSchemeMeal::class, 'meal_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(MenuSchemeRepeatGroup::class, 'group_id');
    }
}
