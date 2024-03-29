<?php

namespace App\Policies;

use App\Models\MenuScheme;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MenuSchemePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string $ability
     * @return bool|null
     */
    public function before(User $user, string $ability): null|bool
    {
        info("$user->id: $ability");

        if ($user->is_admin) {
            return true;
        }
        return null;
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
     * @param MenuScheme $scheme
     * @return bool
     */
    public function view(User $user, MenuScheme $scheme): bool
    {
        return $scheme->user_id === $user->id;
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
     * @param MenuScheme $menuScheme
     * @return Response|bool
     */
    public function update(User $user, MenuScheme $menuScheme): Response|bool
    {
        return $user->id === $menuScheme->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param MenuScheme $menuScheme
     * @return Response|bool
     */
    public function delete(User $user, MenuScheme $menuScheme): Response|bool
    {
        return $user->id === $menuScheme->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param MenuScheme $menuScheme
     * @return Response|bool
     */
    public function restore(User $user, MenuScheme $menuScheme): Response|bool
    {
        return $user->id === $menuScheme->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param MenuScheme $menuScheme
     * @return Response|bool
     */
    public function forceDelete(User $user, MenuScheme $menuScheme): Response|bool
    {
        return $user->id === $menuScheme->user_id;
    }
}
