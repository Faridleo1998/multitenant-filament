<?php

namespace App\Filament\Tenant\Resources\UserResource\Pages;

use App\Domain\Tenant\Actions\CreateTenantUserAction;
use App\Domain\User\Models\CentralUser;
use App\Domain\User\Models\User;
use App\Filament\Tenant\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::Small)
                ->using(
                    function (
                        array $data,
                        CreateTenantUserAction $createTenantUserAction
                    ): Model {

                        $centralUser = CentralUser::firstOrCreate([
                            'email' => $data['email'],
                        ], [
                            'name' => $data['name'],
                            'password' => Hash::make($data['password']),
                        ]);

                        $createTenantUserAction->execute(tenancy()->tenant, $centralUser, $data['roles']);

                        return User::where('global_id', $centralUser->global_id)->first();
                    }
                ),
        ];
    }
}
