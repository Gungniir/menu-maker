<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Image;
use App\Models\Ingredient;
use App\Models\RecipeItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
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
     * @param Category $item
     * @return bool
     */
    public function view(User $user, Category $item): bool
    {
        return $item->creator_id === $user->id;
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
     * @param Category $item
     * @return Response|bool
     */
    public function update(User $user, Category $item): Response|bool
    {
        return $user->id === $item->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Category $item
     * @return Response|bool
     */
    public function delete(User $user, Category $item): Response|bool
    {
        return $user->id === $item->creator_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Category $item
     * @return Response|bool
     */
    public function restore(User $user, Category $item): Response|bool
    {
        return $user->id === $item->creator_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Category $item
     * @return Response|bool
     */
    public function forceDelete(User $user, Category $item): Response|bool
    {
        return false;
    }
}
