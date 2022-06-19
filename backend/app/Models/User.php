<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $telegram_id
 * @property-read Collection|Category[] $categories
 * @property-read int|null $categories_count
 * @property-read Collection|Dish[] $dishes
 * @property-read int|null $dishes_count
 * @property-read Collection|Image[] $images
 * @property-read int|null $images_count
 * @property-read Collection|Ingredient[] $ingredients
 * @property-read int|null $ingredients_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Preparation[] $preparations
 * @property-read int|null $preparations_count
 * @property-read Collection|PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection|Tool[] $tools
 * @property-read int|null $tools_count
 * @property-read Collection|MenuScheme[] $menu_schemes
 * @property-read int|null $menu_schemes_count
 * @property-read Collection|Menu[] $menus
 * @property-read int|null $menus_count
 * @property-read Collection|CartItem[] $cart_items
 * @property-read int|null $cart_items_count
 * @property-read bool $is_admin
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt(Carbon|string $value)
 * @method static Builder|User whereEmail(string $value)
 * @method static Builder|User whereEmailVerifiedAt(Carbon|string $value)
 * @method static Builder|User whereId(int $value)
 * @method static Builder|User whereName(string $value)
 * @method static Builder|User wherePassword(string $value)
 * @method static Builder|User whereRememberToken(string $value)
 * @method static Builder|User whereUpdatedAt(Carbon|string $value)
 * @method static Builder|User whereDeletedAt(Carbon|string $value)
 * @method static Builder|User whereTelegramId(int $value)
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function dishes(): HasMany
    {
        return $this->hasMany(Dish::class, 'creator_id');
    }

    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class, 'creator_id');
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class, 'creator_id');
    }

    public function preparations(): HasMany
    {
        return $this->hasMany(Preparation::class, 'creator_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'creator_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'creator_id');
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function menu_schemes(): HasMany
    {
        return $this->hasMany(MenuScheme::class);
    }

    public function cart_items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->id === 1;
    }
}
