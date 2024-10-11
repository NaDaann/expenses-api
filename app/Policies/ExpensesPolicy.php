<?php

namespace App\Policies;

use App\Models\Expenses;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Expenses $expenses): bool
    {
        return $user->id === $expenses->user_id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Expenses $expenses): bool
    {
        return $user->id === $expenses->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Expenses $expenses): bool
    {
        return $user->id === $expenses->user_id; // Todos os usuários podem criar despesas. Se fosse um projeto real, eu iria sugerir que fosse criada uma tipagem para o usuário, para que fosse possível restringir a criação de despesas para usuários específicos.
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Expenses $expenses): bool
    {
        return $user->id === $expenses->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Expenses $expenses): bool
    {
        return $user->id === $expenses->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Expenses $expenses): bool
    {
        return $user->id === $expenses->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Expenses $expenses): bool
    {
        return $user->id === $expenses->user_id;
    }
}
