<?php

use Spatie\Permission\Models\Permission;

class AddMembershipPreviewPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create the permission
        Permission::create(['name' => 'Membership Preview']);
    }
}
