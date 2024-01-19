<?php

namespace App\Models;

use App\Enums\IngredientUnit;
use Database\Factories\IngredientFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Ingredient
 *
 * @property int $id
 * @property int $creator_id
 * @property string $name Название продукта
 * @property bool $is_perishable Скоропортящийся ли продукт
 * @property string $amount Количество у создателя (decimal)
 * @property IngredientUnit $unit Единица измерения продукта
 * @property string|null $type Категория
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read User|null $creator
 * @property-read Collection|Dish[] $dishes
 * @property-read int|null $dishes_count
 * @property-read Collection|CartItem[] $cart_items
 * @property-read int|null $cart_items_count
 * @method static Builder|Ingredient whereAmount(int $value)
 * @method static Builder|Ingredient whereCreatedAt(Carbon $value)
 * @method static Builder|Ingredient whereCreatorId(int $value)
 * @method static Builder|Ingredient whereDeletedAt(Carbon $value)
 * @method static Builder|Ingredient whereId(int $value)
 * @method static Builder|Ingredient whereIsPerishable(bool $value)
 * @method static Builder|Ingredient whereName(string $value)
 * @method static Builder|Ingredient whereUpdatedAt(Carbon $value)
 * @method static Builder|Ingredient whereUnit(IngredientUnit $value)
 * @method static Builder|Ingredient whereType(string $value)
 * @method static IngredientFactory factory(...$parameters)
 * @method static Builder|Ingredient newModelQuery()
 * @method static Builder|Ingredient newQuery()
 * @method static Builder|Ingredient query()
 * @mixin Eloquent
 */
class Ingredient extends Model
{
    use HasFactory;

    protected $casts = [
        'unit' => IngredientUnit::class,
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class);
    }

    public function cart_items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
