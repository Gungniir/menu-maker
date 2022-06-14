<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\MenuScheme
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection|MenuSchemeRepeatGroup[] $groups
 * @property-read int|null $groups_count
 * @property-read Collection|MenuSchemeMeal[] $meals
 * @property-read int|null $meals_count
 * @property-read Collection|MenuSchemeRepeatItem[] $items
 * @property-read int|null $items_count
 * @property-read User $user
 * @method static Builder|MenuScheme whereCreatedAt(Carbon|string $value)
 * @method static Builder|MenuScheme whereDeletedAt(Carbon|string $value)
 * @method static Builder|MenuScheme whereId(int $value)
 * @method static Builder|MenuScheme whereName(string $value)
 * @method static Builder|MenuScheme whereUpdatedAt(Carbon|string $value)
 * @method static Builder|MenuScheme whereUserId(int $value)
 * @method static Builder|MenuScheme newModelQuery()
 * @method static Builder|MenuScheme newQuery()
 * @method static Builder|MenuScheme query()
 * @method static \Illuminate\Database\Query\Builder|MenuScheme onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|MenuScheme withTrashed()
 * @method static \Illuminate\Database\Query\Builder|MenuScheme withoutTrashed()
 * @mixin Eloquent
 */
class MenuScheme extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function groups(): HasMany
    {
        return $this->hasMany(MenuSchemeRepeatGroup::class, 'scheme_id');
    }

    public function meals(): HasMany
    {
        return $this->hasMany(MenuSchemeMeal::class, 'scheme_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(MenuSchemeRepeatItem::class, MenuSchemeRepeatGroup::class, 'scheme_id', 'group_id');
    }
}
