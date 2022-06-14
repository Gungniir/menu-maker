<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MenuSchemeRepeatGroup
 *
 * @property int $id
 * @property int $scheme_id
 * @property-read Collection|MenuSchemeRepeatItem[] $items
 * @property-read int|null $items_count
 * @property-read MenuScheme $scheme
 * @method static Builder|MenuSchemeRepeatGroup whereId(int $value)
 * @method static Builder|MenuSchemeRepeatGroup whereSchemeId(int $value)
 * @method static Builder|MenuSchemeRepeatGroup newModelQuery()
 * @method static Builder|MenuSchemeRepeatGroup newQuery()
 * @method static Builder|MenuSchemeRepeatGroup query()
 * @mixin Eloquent
 */
class MenuSchemeRepeatGroup extends Model
{
    public $timestamps = false;

    public function items(): HasMany
    {
        return $this->hasMany(MenuSchemeRepeatItem::class, 'group_id');
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(MenuScheme::class, 'scheme_id');
    }
}
