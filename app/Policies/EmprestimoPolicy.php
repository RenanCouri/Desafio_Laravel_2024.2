<?php

namespace App\Policies;

use App\Models\Emprestimo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmprestimoPolicy
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
    public function view(User $user, Emprestimo $emprestimo): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function acessarEmprestimo(User $user): Response
    {
        return $user->cargo!=='administrador'
        ?Response::allow()
        :Response::deny('Você não pode acessar a página de empréstimos como administrador');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Emprestimo $emprestimo)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Emprestimo $emprestimo): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Emprestimo $emprestimo): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Emprestimo $emprestimo): bool
    {
        //
    }
}
