<?php

namespace App\Policies;

use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IngredientPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string $ability
     * @return bool|null
     */
    public function before(User $user, string $ability): null|bool
    {
        return $user->is_admin ? true : null;
    }

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
     * @param Ingredient $item
     * @return bool
     */
    public function view(User $user, Ingredient $item): bool
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
     * @param Ingredient $item
     * @return bool
     */
    public function update(User $user, Ingredient $item): bool
    {
        return $user->id === $item->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Ingredient $item
     * @return bool
     */
    public function delete(User $user, Ingredient $item): bool
    {
        return $user->id === $item->creator_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Ingredient $item
     * @return bool
     */
    public function restore(User $user, Ingredient $item): bool
    {
        return $user->id === $item->creator_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Ingredient $item
     * @return bool
     */
    public function forceDelete(User $user, Ingredient $item): bool
    {
        return false;
    }
}
