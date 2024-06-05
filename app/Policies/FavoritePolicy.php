<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Favorite;
use App\Models\User;

class FavoritePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array(Role::from($user->role), [Role::ADMIN, Role::USER]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Favorite $model): bool
    {
        return in_array(Role::from($user->role), [Role::ADMIN, Role::USER]) || $user->id == $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array(Role::from($user->role), [Role::ADMIN]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Favorite $model): bool
    {
        return in_array(Role::from($user->role), [Role::ADMIN, Role::USER]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Favorite $model): bool
    {
        return in_array(Role::from($user->role), [Role::ADMIN]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Favorite $model): bool
    {
        return in_array(Role::from($user->role), [Role::ADMIN]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Favorite $model): bool
    {
        return in_array(Role::from($user->role), [Role::ADMIN]);
    }
}