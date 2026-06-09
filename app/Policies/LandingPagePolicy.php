<?php

namespace App\Policies;

use App\Models\LandingPage;
use App\Models\User;

class LandingPagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->can('landingPage.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LandingPage $landingPage)
    {
        if ($user->can('landingPage.index')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        if ($user->can('landingPage.create')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LandingPage $landingPage)
    {
        if ($user->can('landingPage.edit')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LandingPage $landingPage)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LandingPage $landingPage)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LandingPage $landingPage)
    {
        //
    }
}
