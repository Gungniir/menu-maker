<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CartItem
 *
 * @property int $id
 * @property int $user_id
 * @property int $ingredient_id
 * @property int $amount
 * @property-read Ingredient $ingredient
 * @property-read User $user
 * @method static Builder|CartItem newModelQuery()
 * @method static Builder|CartItem newQuery()
 * @method static Builder|CartItem query()
 * @method static Builder|CartItem whereAmount(int $value)
 * @method static Builder|CartItem whereId(int $value)
 * @method static Builder|CartItem whereIngredientId(int $value)
 * @method static Builder|CartItem whereUserId(int $value)
 * @mixin Eloquent
 */
class CartItem extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
