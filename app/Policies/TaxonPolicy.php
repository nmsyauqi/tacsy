<?php

namespace App\Policies;

use App\Models\Taxon;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaxonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Taxon $taxon): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Taxon $taxon): bool
    {
        // ATURAN 1: Jika user adalah MASTER, boleh edit punya siapa saja
        if ($user->isMaster()) {
            return true;
        }

        // ATURAN 2: Jika user BIASA (Writer), hanya boleh edit buatan sendiri
        return $user->id === $taxon->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Taxon $taxon): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Taxon $taxon): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Taxon $taxon): bool
    {
        return false;
    }
}
