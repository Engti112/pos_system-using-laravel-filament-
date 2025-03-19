<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can delete another user.
     */
    public function delete(User $user): bool
    {
        return $user->role === 'admin'; // Only admin can delete
    }

    /**
     * Determine if the user can update other users.
     */
    public function update(User $user): bool
    {
        return $user->role === 'admin'; // Only admin can update
    }

    /**
     * Determine if the user can view users.
     */
    public function view(User $user): bool
    {
        return in_array($user->role, ['admin', 'cashier']); // Both can view
    }
}