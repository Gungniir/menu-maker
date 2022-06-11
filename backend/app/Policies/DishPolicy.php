<?php

namespace App\Policies;

use App\Models\Dish;
use App\Models\Image;
use App\Models\Ingredient;
use App\Models\RecipeItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DishPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Dish $dish
     * @return bool
     */
    public function view(User $user, Dish $dish): bool
    {
        return $dish->creator_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Dish $dish
     * @return Response|bool
     */
    public function update(User $user, Dish $dish): Response|bool
    {
        return $user->id === $dish->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Dish $dish
     * @return Response|bool
     */
    public function delete(User $user, Dish $dish): Response|bool
    {
        return $user->id === $dish->creator_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Dish $dish
     * @return Response|bool
     */
    public function restore(User $user, Dish $dish): Response|bool
    {
        return $user->id === $dish->creator_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Dish $dish
     * @return Response|bool
     */
    public function forceDelete(User $user, Dish $dish): Response|bool
    {
        return false;
    }

    /**
     * Когда пользователь может прикрепить к блюду ингредиент
     *
     * @param User $user
     * @param Dish $dish
     * @param Ingredient $ingredient
     * @return Response|bool
     */
    public function storeIngredient(User $user, Dish $dish, Ingredient $ingredient) : Response|bool
    {
        return $dish->creator_id === $user->id && $ingredient->creator_id === $user->id;
    }

    /**
     * Когда пользователь может открепить от блюда ингредиент
     *
     * @param User $user
     * @param Dish $dish
     * @param Ingredient $ingredient
     * @return Response|bool
     */
    public function destroyIngredient(User $user, Dish $dish, Ingredient $ingredient) : Response|bool
    {
        return $dish->creator_id === $user->id && $ingredient->creator_id === $user->id;
    }

    /**
     * Когда пользователь может добавить элемент рецепта
     *
     * @param User $user
     * @param Dish $dish
     * @return Response|bool
     */
    public function storeRecipeItem(User $user, Dish $dish) : Response|bool
    {
        return $dish->creator_id === $user->id;
    }

    /**
     * Когда пользователь может удалить элемент рецепта
     *
     * @param User $user
     * @param Dish $dish
     * @param RecipeItem $recipeItem
     * @return Response|bool
     */
    public function destroyRecipeItem(User $user, Dish $dish, RecipeItem $recipeItem) : Response|bool
    {
        return $dish->creator_id === $user->id && $recipeItem->dish_id === $dish->id;
    }

    /**
     * Когда пользователь может обновить элемент рецепта
     *
     * @param User $user
     * @param Dish $dish
     * @param RecipeItem $recipeItem
     * @return Response|bool
     */
    public function updateRecipeItem(User $user, Dish $dish, RecipeItem $recipeItem) : Response|bool
    {
        return $dish->creator_id === $user->id && $recipeItem->dish_id === $dish->id;
    }

    /**
     * @param User $user
     * @param Dish $dish
     * @param Image $image
     * @return Response|bool
     */
    public function attachImage(User $user, Dish $dish, Image $image) : Response|bool
    {
        return $dish->creator_id === $user->id && $image->creator_id === $user->id;
    }

    /**
     * @param User $user
     * @param Dish $dish
     * @param Image $image
     * @return Response|bool
     */
    public function detachImage(User $user, Dish $dish, Image $image) : Response|bool
    {
        return $dish->creator_id === $user->id && $image->creator_id === $user->id;
    }
}
