<?php

namespace App\Domain\User\Policies;

use App\Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_user');
    }

    public function view(User $user, User $record): bool
    {
        $canNotUpdate = Gate::denies('update', $record);
        $canView = $user->can('view_user');

        return $canNotUpdate && $canView;
    }

    public function create(User $user): bool
    {
        return $user->can('create_user');
    }

    public function update(User $user, User $record): bool
    {
        $canUpdate = $user->can('update_user');
        $isNotDeleted = $record->deleted_at === null;

        return $canUpdate && $isNotDeleted;
    }

    public function delete(User $user, User $record): bool
    {
        $canDelete = $user->can('delete_user');
        $isNotSameAdmin = $user->id !== $record->id;

        return $canDelete && $isNotSameAdmin;
    }
}
