<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\Module;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'users' => [
                'actions' => [
                    'index' => 'user.index',
                    'create'  => 'user.create',
                    'edit'    => 'user.edit',
                    'destroy' => 'user.destroy',
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'destroy'],
                ]
            ],
            'roles' => [
                'actions' => [
                    'index'   => 'role.index',
                    'create'  => 'role.create',
                    'edit'    => 'role.edit',
                    'destroy'  => 'role.destroy'
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'destroy'],
                ],
            ],
            'categories' => [
                'actions' => [
                    'index'   => 'category.index',
                    'create'  => 'category.create',
                    'edit'    => 'category.edit',
                    'destroy'  => 'category.destroy'
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'destroy'],
                    RoleEnum::USER => ['index', 'create', 'edit'],
                    RoleEnum::STAFF => ['index', 'create', 'edit'],
                    RoleEnum::AUTHOR => ['index', 'create', 'edit'],
                    RoleEnum::MEMBER => ['index', 'create', 'edit'],
                    RoleEnum::CREATOR => ['index', 'create', 'edit'],
                ]
            ],
            'tags' => [
                'actions' => [
                    'index'   => 'tag.index',
                    'create'  => 'tag.create',
                    'edit'    => 'tag.edit',
                    'destroy'   => 'tag.destroy',
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'destroy'],
                    RoleEnum::USER => ['index', 'create', 'edit'],
                    RoleEnum::STAFF => ['index', 'create', 'edit'],
                    RoleEnum::AUTHOR => ['index', 'create', 'edit'],
                    RoleEnum::MEMBER => ['index', 'create', 'edit'],
                    RoleEnum::CREATOR => ['index', 'create', 'edit'],
                ]
            ],
            'blogs' => [
                'actions' => [
                    'index'   => 'blog.index',
                    'create'  => 'blog.create',
                    'edit'    => 'blog.edit',
                    'destroy'   => 'blog.destroy',
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'destroy'],
                    RoleEnum::USER => ['index', 'create', 'edit'],
                    RoleEnum::STAFF => ['index', 'create', 'edit'],
                    RoleEnum::AUTHOR => ['index', 'create', 'edit'],
                    RoleEnum::MEMBER => ['index', 'create', 'edit'],
                    RoleEnum::CREATOR => ['index', 'create', 'edit'],
                ]
            ],
            'pages' => [
                'actions' => [
                    'index'   => 'page.index',
                    'create'  => 'page.create',
                    'edit'    => 'page.edit',
                    'destroy'   => 'page.destroy',
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'destroy'],
                    RoleEnum::USER => ['index', 'create', 'edit'],
                    RoleEnum::STAFF => ['index', 'create', 'edit'],
                    RoleEnum::AUTHOR => ['index', 'create', 'edit'],
                    RoleEnum::MEMBER => ['index', 'create', 'edit'],
                    RoleEnum::CREATOR => ['index', 'create', 'edit'],
                ]
            ],
            'settings' => [
                'actions' => [
                    'index'   => 'setting.index',
                    'edit'    => 'setting.edit',
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'edit'],                    
                ],
            ],
            'landingPage' => [
                'actions' => [
                    'index'   => 'landingPage.index',
                    'edit'    => 'landingPage.edit',
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'edit'],
                ],
            ],
        ];

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $userpermision = [];
        $staffpermision = [];
        $authorpermision = [];
        $memberpermision = [];
        $creatorpermision = [];

        foreach ($modules as $key => $value) {
            Module::updateOrCreate(['name' => $key], ['name' => $key, 'actions' => $value['actions']]);
            foreach ($value['actions'] as $key => $permission) {
                if (!Permission::where('name', $permission)->first()) {
                    $permission = Permission::create(['name' => $permission]);
                }
                if (isset($value['roles'])) {
                    foreach ($value['roles'] as $role => $allowed_actions) {
                        if ($role == RoleEnum::USER) {
                            if (in_array($key, $allowed_actions)) {
                                $userpermision[] = $permission;
                            }
                        }
                        if ($role == RoleEnum::STAFF) {
                            if (in_array($key, $allowed_actions)) {
                                $staffpermision[] = $permission;
                            }
                        }
                        if ($role == RoleEnum::AUTHOR) {
                            if (in_array($key, $allowed_actions)) {
                                $authorpermision[] = $permission;
                            }
                        }
                        if ($role == RoleEnum::MEMBER) {
                            if (in_array($key, $allowed_actions)) {
                                $memberpermision[] = $permission;
                            }
                        }
                        if ($role == RoleEnum::CREATOR) {
                            if (in_array($key, $allowed_actions)) {
                                $creatorpermision[] = $permission;
                            }
                        }
                    }
                }
            }
        }


        $admin = Role::create([
            'name' => RoleEnum::ADMIN,
            'system_reserve' => true
        ]);
        $admin->givePermissionTo(Permission::all());
        $user = User::factory()->create([
            'first_name' => 'Villa Bit',
            'last_name' => 'Admin',
            'email' => 'admin@villabit.ai',
            'password' => Hash::make('123456789'),
            'phone' => null,
            'company_name' => 'Villa Bit AI',
            'country' => 'Croatia',
            'account_type' => null,
            'role' => 'super_admin',
            'status' => 'active',
        ]);
        $user->assignRole($admin);

        $userRole = Role::create([
            'name' => RoleEnum::USER,
            'system_reserve' => false
        ]);
        $userRole->givePermissionTo($userpermision);

        $staffRole = Role::create([
            'name' => RoleEnum::STAFF,
            'system_reserve' => false
        ]);
        $staffRole->givePermissionTo($staffpermision);

        $authorRole = Role::create([
            'name' => RoleEnum::AUTHOR,
            'system_reserve' => false
        ]);
        $authorRole->givePermissionTo($authorpermision);

        $memberRole = Role::create([
            'name' => RoleEnum::MEMBER,
            'system_reserve' => false
        ]);
        $memberRole->givePermissionTo($memberpermision);

        $creatorRole = Role::create([
            'name' => RoleEnum::CREATOR,
            'system_reserve' => false
        ]);
        $creatorRole->givePermissionTo($creatorpermision);
    }
}
