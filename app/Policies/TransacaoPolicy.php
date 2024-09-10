<?php

namespace App\Policies;

use App\Models\Conta;
use App\Models\Transacao;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransacaoPolicy
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
    public function view(User $user, Transacao $transacao): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function acessarExtrato(User $user): Response
    {
        return $user->cargo!=='administrador'
        ?Response::allow()
        :Response::deny();
    }
    public function acessarSaqueDeposito(User $user): Response
    {
        return $user->cargo!=='usuario_comum'
        ?Response::allow()
        :Response::deny();
    }
    public function saqueDeposito(User $user,Conta $model)
    {
        if($user->cargo==='usuario_comum')
           return Response::deny();
        if($model===null )
            return Response::denyAsNotFound('conta não encontrada');
        $model=$model->user;   
        return $user->cargo==='administrador'
        ||( $user->cargo==='gerente'&&in_array($model,$user->getUsuariosComuns()) )
                ? Response::allow()
                : Response::deny('Você não tem permissão de agir sobre essa conta');
    }
    public function requerirTransferencia(User $user, Conta $model)
    {
        if($user->cargo==='administrador')
          return Response::deny();
        if($model===null )
          return Response::denyAsNotFound('conta não encontrada');
        return Response::allow();
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transacao $transacao): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transacao $transacao): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Transacao $transacao): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Transacao $transacao): bool
    {
        //
    }
}
