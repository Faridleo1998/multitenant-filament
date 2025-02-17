<?php

namespace Database\Seeders\Tenant;

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
                'guard_name' => 'web',
                'permissions' => [
                    'view_role',
                    'view_any_role',
                    'create_role',
                    'update_role',
                    'delete_role',
                    'delete_any_role',
                    'view_any_user',
                    'view_user',
                    'create_user',
                    'update_user',
                    'delete_user',
                    'view_any_category',
                    'view_category',
                    'create_category',
                    'update_category',
                    'delete_category',
                    'view_any_tag',
                    'view_tag',
                    'create_tag',
                    'update_tag',
                    'delete_tag',
                    'view_any_keyword',
                    'view_keyword',
                    'create_keyword',
                    'update_keyword',
                    'delete_keyword',
                    'view_any_profession',
                    'view_profession',
                    'create_profession',
                    'update_profession',
                    'delete_profession',
                ],
            ],
            [
                'name' => 'sales_manager',
                'guard_name' => 'web',
                'permissions' => [],
            ],
        ];

        $directPermissions = '[]';

        static::makeRolesWithPermissions(json_encode($rolesWithPermissions));
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }
}
