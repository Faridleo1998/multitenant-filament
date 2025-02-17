<?php

namespace App\Domain\Education\Policies;

use App\Domain\Education\Models\Profession;
use App\Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class ProfessionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_profession');
    }

    public function view(User $user, Profession $record): bool
    {
        $canNotUpdate = Gate::denies('update', $record);
        $canView = $user->can('view_profession');

        return $canNotUpdate && $canView;
    }

    public function create(User $user): bool
    {
        return $user->can('create_profession');
    }

    public function update(User $user): bool
    {
        return $user->can('update_profession');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete_profession');
    }
}
