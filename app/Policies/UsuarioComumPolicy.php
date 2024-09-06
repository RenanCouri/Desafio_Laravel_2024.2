<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UsuarioComumPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show( User $user,int $id)
    {
        $model=User::find($id);
        var_dump($user);
        dd($model);
        return true;
        return $model!==null && $model->cargo==='usuario_comum'
        &&(($user->id===$model->id)
        ||$user->cargo==='administrador'
        ||( $user->cargo==='gerente'&&in_array($model,$user->getUsuariosComuns()) ))
                ? Response::allow()
                : Response::deny('Esse usuário comum não existe ou você não tem permissão de agir sobre o cadastro dele');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->cargo==='administrador'
                ? Response::allow()
                : Response::deny('Você não pode cadastrar gerentes.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
