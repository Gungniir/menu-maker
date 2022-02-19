<?php

namespace App\Models;

use Database\Factories\PreparationFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Preparation
 *
 * @property-read User|null $creator
 * @property-read Collection|Dish[] $dishes
 * @property-read int|null $dishes_count
 * @method static PreparationFactory factory(...$parameters)
 * @method static Builder|Preparation newModelQuery()
 * @method static Builder|Preparation newQuery()
 * @method static Builder|Preparation query()
 * @mixin Eloquent
 * @property int $id
 * @property int $creator_id
 * @property int|null $dish_id Если подготовка - готовка блюда
 * @property string|null $name Если подготовка - не готовка блюда
 * @property int|null $preparation_time Если подготовка - не готовка блюда (в минутах)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|Preparation whereCreatedAt(Carbon $value)
 * @method static Builder|Preparation whereCreatorId(int $value)
 * @method static Builder|Preparation whereDeletedAt(Carbon $value)
 * @method static Builder|Preparation whereDishId(int $value)
 * @method static Builder|Preparation whereId(int $value)
 * @method static Builder|Preparation whereName(string $value)
 * @method static Builder|Preparation wherePreparationTime(int $value)
 * @method static Builder|Preparation whereUpdatedAt(Carbon $value)
 */
class Preparation extends Model
{
    use HasFactory;

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class);
    }
}
