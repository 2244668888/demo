<?php

namespace Database\Seeders;

use App\Models\FamilyUser;
use App\Models\MoreUser;
use App\Models\PersonalUser;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Admin']);

        $user = User::create([
            'user_name' => 'admin',
            'full_name' => 'Mr admin',
            'email' => 'admin@gmail.com',
            'is_active' => 'yes',
            'password' => bcrypt('Admin@321'),
            'role_ids' => json_encode(["1"]),
        ]);

        $userFamily = FamilyUser::create([
            'spouse_name' => 'admin',
        ]);

        $userMore = MoreUser::create([
            'annual_leave' => 'annual leave',
        ]);

        $userPerosnal = PersonalUser::create([
            'gender' => 'male',
        ]);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
