<?php

namespace App\Models;

use Database\Factories\ToolFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Tool
 *
 * @property-read User|null $creator
 * @property-read Collection|Dish[] $dishes
 * @property-read int|null $dishes_count
 * @method static ToolFactory factory(...$parameters)
 * @method static Builder|Tool newModelQuery()
 * @method static Builder|Tool newQuery()
 * @method static Builder|Tool query()
 * @mixin Eloquent
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property int $amount Количтсвео у создателя
 * @property bool $reusable Можно ли применять одновременно при готовке разных блюд
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|Tool whereAmount($value)
 * @method static Builder|Tool whereCreatedAt($value)
 * @method static Builder|Tool whereCreatorId($value)
 * @method static Builder|Tool whereDeletedAt($value)
 * @method static Builder|Tool whereId($value)
 * @method static Builder|Tool whereName($value)
 * @method static Builder|Tool whereReusable($value)
 * @method static Builder|Tool whereUpdatedAt($value)
 */
class Tool extends Model
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
