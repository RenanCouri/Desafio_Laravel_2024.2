<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
    public function acaoUsuarioComum(User $user, int $model): Response
    {
        $model=User::find($model);
        if($model===null || $model->cargo!=='usuario_comum')
            return Response::denyAsNotFound('usuario comum não encontrado');
           
        return (($user->id===$model->id)||$user->cargo==='administrador'||( $user->cargo==='gerente'&&in_array($model,$user->getUsuariosComuns()) ))
                ? Response::allow()
                : Response::deny('Você não tem permissão de agir sobre o cadastro dele');
    }
    public function acaoGerente(User $user, int $model): Response
    {
        $model=User::find($model);
        if($model===null || $model->cargo!=='gerente')
            return Response::denyAsNotFound('Gerente não encontrado');
        return ($user->id===$model->id)
        ||$user->cargo==='administrador'
        
                ? Response::allow()
                : Response::deny('Esse gerente não existe ou você não tem permissão de agir sobre o cadastro dele');
    }
    public function acaoAdministrador(User $user, int $model): Response
    {
        $model=User::find($model);
        if($model===null || $model->cargo!=='administrador')
            return Response::denyAsNotFound('Administrador não encontrado');
        return $user->cargo==='administrador'
        &&($user->usuario_responsavel_id!==$model->id)
        
        
                ? Response::allow()
                : Response::deny('Esse admninistrador não existe ou você não tem permissão de agir sobre o cadastro dele');
    }

    /**
     * Determine whether the user can create models.
     */
    public function createUsuarioComum(User $user): Response
    {
        return $user->cargo!=='usuario_comum'
        ? Response::allow()
                : Response::deny('Você não pode cadastrar usuarios comuns.');
    }
    public function paginaGerente(User $user): Response
    {
        return $user->cargo!=='usuario_comum'
        ? Response::allow()
                : Response::deny('Você não pode acessar a página de gerentes.');
    }
    public function createGerente(User $user): Response
    {
        return $user->cargo!=='usuario_comum'
                ? Response::allow()
                : Response::deny('Você não pode cadastrar gerentes.');
    }
    public function paginaAdministrador(User $user): Response
    {
        return $user->cargo==='administrador'
        ? Response::allow()
                : Response::deny('Você não pode acessar a página de administradores.');
    }
    public function createAdministrador(User $user): Response
    {
        return $user->cargo==='administrador'
                ? Response::allow()
                : Response::deny('Você não pode cadastrar administradores.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        //
    }
}
