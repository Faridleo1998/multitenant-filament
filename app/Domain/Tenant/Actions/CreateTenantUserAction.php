<?php

namespace App\Domain\Tenant\Actions;

use App\Domain\Tenant\Models\Tenant;
use App\Domain\User\Models\CentralUser;
use App\Domain\User\Models\User;

class CreateTenantUserAction
{
    public function execute(Tenant $tenant, CentralUser $centralUser): void
    {
        $tenant->run(function () use ($centralUser) {
            $user = User::withTrashed()->where('global_id', $centralUser->global_id)->first();
            if ($user) {
                $user->restore();
            } else {
                $user = User::create([
                    'global_id' => $centralUser->global_id,
                    'name' => $centralUser->name,
                    'email' => $centralUser->email,
                    'password' => $centralUser->password,
                ]);
            }
        });
    }
}
