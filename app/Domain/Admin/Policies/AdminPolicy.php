<?php

namespace App\Domain\Admin\Policies;

use App\Domain\Admin\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class AdminPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view_any_admin');
    }

    public function view(Admin $admin, Admin $record): bool
    {
        $canNotUpdate = Gate::denies('update', $record);
        $canView = $admin->can('view_admin');

        return $canNotUpdate && $canView;
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create_admin');
    }

    public function update(Admin $admin, Admin $record): bool
    {
        $canUpdate = $admin->can('update_admin');
        $isNotDeleted = $record->deleted_at === null;

        return $canUpdate && $isNotDeleted;
    }

    public function delete(Admin $admin, Admin $record): bool
    {
        $canDelete = $admin->can('delete_admin');
        $isNotSameAdmin = $admin->id !== $record->id;
        $isNotSuperAdmin = ! $record->is_super_admin;

        return $canDelete && $isNotSameAdmin && $isNotSuperAdmin;
    }

    public function restore(Admin $admin): bool
    {
        return $admin->can('restore_admin');
    }
}
