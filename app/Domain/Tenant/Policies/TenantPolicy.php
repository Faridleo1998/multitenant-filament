<?php

namespace App\Domain\Tenant\Policies;

use App\Domain\Admin\Models\Admin;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class TenantPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view_any_tenant');
    }

    public function view(Admin $admin, Tenant $record): bool
    {
        $canNotUpdate = Gate::denies('update', $record);
        $canView = $admin->can('view_tenant');

        return $canNotUpdate && $canView;
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create_tenant');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update_tenant');
    }
}
