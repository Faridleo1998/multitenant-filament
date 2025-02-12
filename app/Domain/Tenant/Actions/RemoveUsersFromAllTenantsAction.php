<?php

namespace App\Domain\Tenant\Actions;

use App\Domain\Tenant\Models\Tenant;
use App\Domain\User\Models\CentralUser;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

class RemoveUsersFromAllTenantsAction
{
    public function execute(Collection|CentralUser $centralUser): void
    {
        $users = $centralUser instanceof Collection ? $centralUser : collect([$centralUser]);

        $globalIds = $users->pluck('global_id');

        $tenants = $users->flatMap(fn(CentralUser $user) => $user->tenants)->unique();

        $tenants->each(fn(Tenant $tenant) => $tenant->run(
            function () use ($globalIds, $tenant) {
                User::whereIn('global_id', $globalIds)->delete();
                $tenant->centralUsers()->detach($globalIds);
            }
        ));
    }
}
