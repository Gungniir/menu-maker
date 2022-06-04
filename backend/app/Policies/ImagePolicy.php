<?php

namespace App\Policies;

use App\Models\Dish;
use App\Models\Image;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ImagePolicy
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
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Image $image
     * @return bool
     */
    public function view(User $user, Image $image): bool
    {
        return $image->creator_id === $user->id;
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
     * @param Image $image
     * @return bool
     */
    public function update(User $user, Image $image): bool
    {
        return $user->id === $image->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Image $image
     * @return bool
     */
    public function delete(User $user, Image $image): bool
    {
        return $user->id === $image->creator_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Image $image
     * @return bool
     */
    public function restore(User $user, Image $image): bool
    {
        return $user->id === $image->creator_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Dish $dish
     * @return bool
     */
    public function forceDelete(User $user, Dish $dish): bool
    {
        return false;
    }
}
