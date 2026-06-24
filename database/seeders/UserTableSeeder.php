<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;
use Str;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $super = User::updateOrCreate(
            ['email' => 'admin@mpdev.my.id'],
            [
                'role' => 'admin',
                'avatar' => 'uploads/avatar.png',
                'name' => 'Admin',
                'password' => Hash::make('@Jembu123'),
            ]
        );

        $roleSuperAdmin = Role::firstOrCreate(['name' => 'superadmin']);
        //create permission
        $permissions = [
          
            [
                'group_name' => 'Appearance',
                'permissions' => [
                    'about',
                    'blogs',
                    'blogs-category',
                    'blog-tags',
                    'faq',
                    'features',
                    'team',
                    'language',
                    'menu',
                    'custom-page',
                    'partners',
                    'seo',
                    'testimonials'


                ]
            ],
            [
                'group_name' => 'Site Settings',
                'permissions' => [
                    'page-settings',
                    'admin',
                    'developer-settings',
                    'roles',


                ]
            ],
            [
                'group_name' => 'User Logs',
                'permissions' => [
                    'apps',
                    'contacts',
                    'customer',
                    'device',
                    'notification',
                    'schedule',
                    'templates',
                    'message-transactions'
                ]
            ],
            [
                'group_name' => 'SAAS Functionalities',
                'permissions' => [
                    'cron-job',
                    'gateways',
                    'order',
                    'subscriptions',
                    'support',
                ]
            ],


        ];

        //assign permission

        foreach ($permissions as $key => $row) {


            foreach ($row['permissions'] as $per) {
                $permission = Permission::firstOrCreate(['name' => $per, 'group_name' => $row['group_name']]);
                if (!$roleSuperAdmin->hasPermissionTo($permission)) {
                    $roleSuperAdmin->givePermissionTo($permission);
                }
                if (!$super->hasRole($roleSuperAdmin)) {
                    $super->assignRole($roleSuperAdmin);
                }
            }
        }
    }
}
