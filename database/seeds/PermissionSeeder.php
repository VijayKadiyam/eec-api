<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Permission::truncate();
    Permission::create(['name' => 'Settings']); // 1
    Permission::create(['name' => 'Manage Permissions']); // 2
    Permission::create(['name' => 'Manage Units']); // 3
    Permission::create(['name' => 'Reset Password']); // 4
    Permission::create(['name' => 'Manage Organizations']); // 5
  }
}
