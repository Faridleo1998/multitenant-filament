<?php

namespace App\Policies;

use App\Domain\Admin\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view_any_role');
    }

    public function view(Admin $admin, Role $role): bool
    {
        $canNotUpdate = Gate::denies('update', $role);
        $canView = $admin->can('view_role');

        return $canNotUpdate && $canView;
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create_role');
    }

    public function update(Admin $admin, Role $role): bool
    {
        $canUpdate = $admin->can('update_role');
        $hasNotRole = ! $admin->hasRole($role->name);
        $isNotSuperAdmin = $role->id !== 1;

        return $canUpdate && $hasNotRole && $isNotSuperAdmin;
    }

    public function delete(Admin $admin, Role $role): bool
    {
        $hasNotRole = ! $admin->hasRole($role->name);
        $canDelete = $admin->can('delete_role');
        $isNotSuperAdmin = $role->id !== 1;

        return $canDelete && $hasNotRole && $isNotSuperAdmin;
    }
}
