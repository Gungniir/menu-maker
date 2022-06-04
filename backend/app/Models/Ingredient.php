<?php

namespace App\Models;

use App\Enums\IngredientUnit;
use Database\Factories\IngredientFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Ingredient
 *
 * @property-read User|null $creator
 * @method static IngredientFactory factory(...$parameters)
 * @method static Builder|Ingredient newModelQuery()
 * @method static Builder|Ingredient newQuery()
 * @method static Builder|Ingredient query()
 * @mixin Eloquent
 * @property int $id
 * @property int $creator_id
 * @property string $name Название продукта
 * @property bool $is_perishable Скоропортящийся ли продукт
 * @property int $amount Количество у создателя
 * @property IngredientUnit $unit Единица измерения продукта
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|Ingredient whereAmount(int $value)
 * @method static Builder|Ingredient whereCreatedAt(Carbon $value)
 * @method static Builder|Ingredient whereCreatorId(int $value)
 * @method static Builder|Ingredient whereDeletedAt(Carbon $value)
 * @method static Builder|Ingredient whereId(int $value)
 * @method static Builder|Ingredient whereIsPerishable(bool $value)
 * @method static Builder|Ingredient whereName(string $value)
 * @method static Builder|Ingredient whereUpdatedAt(Carbon $value)
 */
class Ingredient extends Model
{
    use HasFactory;

    protected $casts = [
        'unit' => IngredientUnit::class,
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
