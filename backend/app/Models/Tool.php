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
use Illuminate\Support\Carbon;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|Tool whereAmount(int $value)
 * @method static Builder|Tool whereCreatedAt(Carbon $value)
 * @method static Builder|Tool whereCreatorId(int $value)
 * @method static Builder|Tool whereDeletedAt(Carbon $value)
 * @method static Builder|Tool whereId(int $value)
 * @method static Builder|Tool whereName(string $value)
 * @method static Builder|Tool whereReusable(bool $value)
 * @method static Builder|Tool whereUpdatedAt(Carbon $value)
 */
class Tool extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class);
    }
}
