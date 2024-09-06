<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdministradorPolicy
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
    public function realizarAcao(User $user, User $model): bool
    {
        var_dump($user);
        dd($model);
        return $model!==null && $model->cargo==='administrador'
        &&$user->cargo==='administrador'
        &&($user->usuario_responsavel_id!==$model->id)
        ? Response::allow()
        : Response::deny('Esse administrador não existe ou você não tem permissão de agir sobre o cadastro dele');
    }

    /**
     * Determine whether the user can create models.
     */
    public function criar(User $user): bool
    {
        return $user->cargo==='administrador'
                ? Response::allow()
                : Response::deny('Você não pode cadastrar gerentes.');
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
