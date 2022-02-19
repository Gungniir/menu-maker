<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\CategoryFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string $group
 * @property-read User|null $creator
 * @property-read Collection|Dish[] $dishes
 * @property-read int|null $dishes_count
 * @method static CategoryFactory factory(...$parameters)
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @mixin Eloquent
 * @method static Builder|Category whereCreatedAt(Carbon $value)
 * @method static Builder|Category whereCreatorId(int $value)
 * @method static Builder|Category whereDeletedAt(Carbon $value)
 * @method static Builder|Category whereId(int $value)
 * @method static Builder|Category whereName(string $value)
 * @method static Builder|Category whereUpdatedAt(Carbon $value)
 * @method static Builder|Category whereGroup(string $value)
 */
class Category extends Model
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
