<?php

namespace App\Policies;

use App\Models\User;

class UserListPolicy
{
    /**
     * Only admins can view the list of users.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can view user details.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can update user details.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can delete users.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
