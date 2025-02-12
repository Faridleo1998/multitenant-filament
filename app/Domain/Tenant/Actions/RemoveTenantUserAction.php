<?php

namespace App\Domain\Tenant\Actions;

use App\Domain\Tenant\Models\Tenant;
use App\Domain\User\Models\CentralUser;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

class RemoveTenantUserAction
{
    public function execute(
        Collection|Tenant $tenants,
        Collection|CentralUser $centralUser
    ): void {
        $users = $centralUser instanceof Collection ? $centralUser : collect([$centralUser]);

        $tenants = $tenants instanceof Collection ? $tenants : collect([$tenants]);

        $tenants->each(function (Tenant $tenant) use ($users): void {
            $tenant->run(function () use ($users): void {
                $users->each(
                    fn(CentralUser $user): mixed => User::where('global_id', $user->global_id)->delete()
                );
            });
        });
    }
}
