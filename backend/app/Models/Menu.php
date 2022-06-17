<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

/**
 * App\Models\Menu
 *
 * @property int $id
 * @property int $user_id
 * @property string $start_date Дата начала недели
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $amount На сколько человек готовится меню
 * @property-read Collection|MenuMeal[] $meals
 * @property-read int|null $meals_count
 * @property-read User $user
 * @method static Builder|Menu whereCreatedAt(Carbon|string $value)
 * @method static Builder|Menu whereDeletedAt(Carbon|string $value)
 * @method static Builder|Menu whereId(int $value)
 * @method static Builder|Menu whereStartDate(Carbon|Date|string $value)
 * @method static Builder|Menu whereUpdatedAt(Carbon|string $value)
 * @method static Builder|Menu whereUserId(int $value)
 * @method static Builder|Menu whereAmount(int $value)
 * @method static Builder|Menu newModelQuery()
 * @method static Builder|Menu newQuery()
 * @method static Builder|Menu query()
 * @method static \Illuminate\Database\Query\Builder|Menu onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Menu withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Menu withoutTrashed()
 * @mixin Eloquent
 */
class Menu extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function meals(): HasMany
    {
        return $this->hasMany(MenuMeal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
