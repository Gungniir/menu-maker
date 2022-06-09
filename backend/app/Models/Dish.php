<?php

namespace App\Models;

use Database\Factories\DishFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Dish
 *
 * @property-read Collection|Category[] $categories
 * @property-read int|null $categories_count
 * @property-read User|null $creator
 * @property-read Collection|Image[] $images
 * @property-read int|null $images_count
 * @property-read Collection|Preparation[] $preparations
 * @property-read int|null $preparations_count
 * @property-read Collection|RecipeItem[] $recipeItems
 * @property-read int|null $recipe_items_count
 * @property-read Collection|Tool[] $tools
 * @property-read int|null $tools_count
 * @method static DishFactory factory(...$parameters)
 * @method static Builder|Dish newModelQuery()
 * @method static Builder|Dish newQuery()
 * @method static Builder|Dish query()
 * @mixin Eloquent
 * @property int $id
 * @property int $creator_id Создатель блюда
 * @property string $name Название блюда
 * @property bool $is_archive Находится ли блюдо в архиве
 * @property int|null $cooking_time Время приготовления в минутах
 * @property int|null $kcal Килокалории в расчёте на одну порцию
 * @property int|null $proteins Белки в расчёте на одну порцию
 * @property int|null $fats Жиры в расчёте на одну порцию
 * @property int|null $carbohydrates Углеводы в расчёте на одну порцию
 * @property string|null $link Ссылка на источник блюда
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|Dish whereCarbohydrates(int $value)
 * @method static Builder|Dish whereCookingTime(int $value)
 * @method static Builder|Dish whereCreatedAt(Carbon $value)
 * @method static Builder|Dish whereCreatorId(int $value)
 * @method static Builder|Dish whereDeletedAt(Carbon $value)
 * @method static Builder|Dish whereFats(int $value)
 * @method static Builder|Dish whereId(int $value)
 * @method static Builder|Dish whereIsArchive(bool $value)
 * @method static Builder|Dish whereKcal(int $value)
 * @method static Builder|Dish whereLink(string $value)
 * @method static Builder|Dish whereName(string $value)
 * @method static Builder|Dish whereProteins(int $value)
 * @method static Builder|Dish whereUpdatedAt(Carbon $value)
 */
class Dish extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function recipeItems(): HasMany
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function preparations(): BelongsToMany
    {
        return $this->belongsToMany(Preparation::class);
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'dish_category');
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)->using(DishIngredient::class)->withTimestamps()->withPivot('id', 'amount');
    }
}
