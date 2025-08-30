<?php

namespace App\Policies;

use App\Models\ContactQuery;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactQueryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin can view all contact queries
        if (strtolower($user->role->name) === 'admin') {
            return true;
        }

        // Other users can only view their own queries
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContactQuery $contactQuery): bool
    {
        // Admin can view any contact query
        if (strtolower($user->role->name) === 'admin') {
            return true;
        }

        // Users can view their own queries
        return $contactQuery->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create contact queries
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContactQuery $contactQuery): bool
    {
        // Only admins can update contact queries
        return strtolower($user->role->name) === 'admin';
    }

    /**
     * Determine whether the user can update status of the model.
     */
    public function update_status(User $user, ContactQuery $contactQuery): bool
    {
        // Only admins can update contact query status
        return strtolower($user->role->name) === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContactQuery $contactQuery): bool
    {
        // Only admins can delete contact queries
        return strtolower($user->role->name) === 'admin';
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContactQuery $contactQuery): bool
    {
        // Only admins can restore contact queries
        return strtolower($user->role->name) === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContactQuery $contactQuery): bool
    {
        // Only admins can permanently delete contact queries
        return strtolower($user->role->name) === 'admin';
    }

    /**
     * Determine whether the user can export contact queries.
     */
    public function export(User $user): bool
    {
        // Only admins can export contact queries
        return strtolower($user->role->name) === 'admin';
    }
}
