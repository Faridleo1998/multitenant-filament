<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = [
            [
                'name' => 'super_admin',
                'guard_name' => 'admin',
                'permissions' => [
                    'view_admin',
                    'view_any_admin',
                    'create_admin',
                    'update_admin',
                    'delete_admin',
                    'restore_admin',
                    'view_role',
                    'view_any_role',
                    'create_role',
                    'update_role',
                    'delete_role',
                    'delete_any_role',
                    'view_tenant',
                    'view_any_tenant',
                    'create_tenant',
                    'update_tenant',
                    'attach_central_user_tenant',
                    'detach_central_user_tenant',
                    'view_central::user',
                    'view_any_central::user',
                    'create_central::user',
                    'update_central::user',
                    'delete_central::user',
                    'restore_central::user',
                    'attach_tenant_central::user',
                    'detach_tenant_central::user',
                ],
            ],
        ];

        $directPermissions = '[]';

        static::makeRolesWithPermissions(json_encode($rolesWithPermissions));
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }
}
