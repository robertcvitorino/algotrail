<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"Super Administrador","guard_name":"web","permissions":["view_any_question","create_question","update_question","delete_question","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_any_trail","create_trail","update_trail","delete_trail","view_any_user","create_user","update_user","delete_user","page_MenuPage","page_MyProfilePage","play_trail"]},{"name":"Professor","guard_name":"web","permissions":["view_any_question","create_question","update_question","delete_question","view_any_trail","create_trail","update_trail","delete_trail","page_MyProfilePage"]},{"name":"Estudante","guard_name":"web","permissions":["view_any_trail","page_MyProfilePage","play_trail"]}]';
        $directPermissions = '{"0":{"name":"view_question","guard_name":"web"},"4":{"name":"restore_question","guard_name":"web"},"5":{"name":"restore_any_question","guard_name":"web"},"6":{"name":"replicate_question","guard_name":"web"},"7":{"name":"reorder_question","guard_name":"web"},"9":{"name":"delete_any_question","guard_name":"web"},"10":{"name":"force_delete_question","guard_name":"web"},"11":{"name":"force_delete_any_question","guard_name":"web"},"18":{"name":"view_trail","guard_name":"web"},"22":{"name":"restore_trail","guard_name":"web"},"23":{"name":"restore_any_trail","guard_name":"web"},"24":{"name":"replicate_trail","guard_name":"web"},"25":{"name":"reorder_trail","guard_name":"web"},"27":{"name":"delete_any_trail","guard_name":"web"},"28":{"name":"force_delete_trail","guard_name":"web"},"29":{"name":"force_delete_any_trail","guard_name":"web"},"30":{"name":"view_user","guard_name":"web"},"34":{"name":"restore_user","guard_name":"web"},"35":{"name":"restore_any_user","guard_name":"web"},"36":{"name":"replicate_user","guard_name":"web"},"37":{"name":"reorder_user","guard_name":"web"},"39":{"name":"delete_any_user","guard_name":"web"},"40":{"name":"force_delete_user","guard_name":"web"},"41":{"name":"force_delete_any_user","guard_name":"web"}}';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
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
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

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
}
