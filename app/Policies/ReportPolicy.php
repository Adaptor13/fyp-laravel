<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        // Admin and gov_official can view all cases
        if (in_array(strtolower($user->role->name), ['admin', 'gov_official'])) {
            return true;
        }

        // Other roles can only view assigned cases
        if (in_array(strtolower($user->role->name), ['social_worker', 'law_enforcement', 'healthcare'])) {
            return $report->assignees()->where('user_id', $user->id)->exists();
        }

        // Public users can only view their own reports
        if (strtolower($user->role->name) === 'public_user') {
            return $report->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can post messages to the case.
     */
    public function post(User $user, Report $report): bool
    {
        // First check if user can view the case
        if (!$this->view($user, $report)) {
            return false;
        }

        // Check if case is not closed
        if ($report->report_status === 'Closed') {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        // Admin and gov_official can update all cases
        if (in_array(strtolower($user->role->name), ['admin', 'gov_official'])) {
            return true;
        }

        // Other roles can only update assigned cases
        if (in_array(strtolower($user->role->name), ['social_worker', 'law_enforcement', 'healthcare'])) {
            return $report->assignees()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        // Only admin can delete cases
        return strtolower($user->role->name) === 'admin';
    }
}
