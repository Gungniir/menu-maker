<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Image;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\MenuScheme;
use App\Models\Tool;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\DishPolicy;
use App\Policies\ImagePolicy;
use App\Policies\IngredientPolicy;
use App\Policies\MenuPolicy;
use App\Policies\MenuSchemePolicy;
use App\Policies\ToolPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Dish::class => DishPolicy::class,
        User::class => UserPolicy::class,
        Image::class => ImagePolicy::class,
        Tool::class => ToolPolicy::class,
        Ingredient::class => IngredientPolicy::class,
        MenuScheme::class => MenuSchemePolicy::class,
        Menu::class => MenuPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
