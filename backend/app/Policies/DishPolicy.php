<?php

namespace App\Policies;

use App\Models\Dish;
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
}
