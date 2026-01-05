<?php

namespace App\Policies;

use App\Models\Correspondence;
use App\Models\User;

class CorrespondencePolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->is_super_admin === 'Yes' || $user->hasAnyRole(['super-admin', 'admin'])) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view correspondence');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Correspondence $correspondence): bool
    {
        if (! $user->can('view correspondence')) {
            return false;
        }

        return $correspondence->created_by === $user->id ||
               $correspondence->current_holder_id === $user->id ||
               $correspondence->addressed_to_user_id === $user->id ||
               $correspondence->movements()->where(function ($q) use ($user) {
                   $q->where('from_user_id', $user->id)
                       ->orWhere('to_user_id', $user->id);
               })->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create correspondence');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Correspondence $correspondence): bool
    {
        if (! $user->can('edit correspondence')) {
            return false;
        }

        // Only creator or current holder can edit
        return $correspondence->created_by === $user->id ||
               $correspondence->current_holder_id === $user->id;
    }

    /**
     * Determine whether the user can update movements.
     */
    public function updateMovement(User $user, Correspondence $correspondence): bool
    {
        // User must at least be able to view it
        if (! $this->view($user, $correspondence)) {
            return false;
        }

        // Further logic can be added here if needed,
        // but usually the controller handles the specific movement check.
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Correspondence $correspondence): bool
    {
        return $user->can('delete correspondence') && $correspondence->created_by === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Correspondence $correspondence): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Correspondence $correspondence): bool
    {
        return false;
    }
}
