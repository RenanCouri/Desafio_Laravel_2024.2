<?php

namespace App\Policies;

use App\Models\Pendencia;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PendenciaPolicy
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
    public function view(User $user, Pendencia $pendencia): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function acessarPendencia(User $user): Response
    {
        return $user->cargo=='gerente'
        ?Response::allow()
        :Response::deny('Você não tem permissão para acessar a página de pendências');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function acaoPendencia(User $user, Pendencia $pendencia=null)
    {
        if($pendencia===null)
           return Response::denyAsNotFound('Pendência não encotrada');
        if($pendencia->autoridade_id!==$user->id)
          return Response::deny('Você não tem permissão para acessar essa pendência');
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pendencia $pendencia): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pendencia $pendencia): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pendencia $pendencia): bool
    {
        //
    }
}
