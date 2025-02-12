<?php

namespace App\Domain\User\Policies;

use App\Domain\Admin\Models\Admin;
use App\Domain\User\Models\CentralUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class CentralUserPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view_any_central::user');
    }

    public function view(Admin $admin, CentralUser $record): bool
    {
        $canNotUpdate = Gate::denies('update', $record);
        $canView = $admin->can('view_central::user');

        return $canNotUpdate && $canView;
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create_central::user');
    }

    public function update(Admin $admin, CentralUser $record): bool
    {
        $canUpdate = $admin->can('update_central::user');
        $isNotDeleted = $record->deleted_at === null;

        return $canUpdate && $isNotDeleted;
    }

    public function delete(Admin $admin): bool
    {
        return $admin->can('delete_central::user');
    }

    public function restore(Admin $admin): bool
    {
        return $admin->can('restore_central::user');
    }
}
