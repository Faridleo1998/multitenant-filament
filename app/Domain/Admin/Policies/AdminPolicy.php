<?php

namespace App\Domain\Admin\Policies;

use App\Domain\Admin\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view_any_admin');
    }

    public function view(Admin $admin): bool
    {
        return $admin->can('view_admin');
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create_admin');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update_admin');
    }

    public function delete(Admin $admin): bool
    {
        return $admin->can('delete_admin');
    }

    public function restore(Admin $admin): bool
    {
        return $admin->can('restore_admin');
    }
}
