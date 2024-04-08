<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IdeaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Idea $idea): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Idea $idea): bool   //Obs. El $user que recibe aqui ya esta configurado para que sea el usuario autenticado, esta es una ventaja de utilizar el comando   "php artisan make:policy IdeaPolicy --model=Idea"
    {
        //return $user->id == $idea->user_id;   forma 1
        return $idea->user()->is($user);   //forma 2  (Hacen exactamente lo mismo)
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Idea $idea): bool
    {
        return $idea->user()->is($user);
        /*return $this->update($user,$idea); Esta forma tambiés es valida en este caso en particular, en esta forma debo enviarle el $user*/
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Idea $idea): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Idea $idea): bool
    {
        //
    }

     /**
      * Determine where the user can update the likes field.
      */

    public function updateLikes(User $user, Idea $idea): bool
    {
        return $idea->user()->isNot($user);
    }
}
