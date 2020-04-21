<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\User;
use App\UserPermission;

class UserPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = Permission::get();
        $user = User::first();

        $datas = array();
        foreach($permission as $perm) {
            array_push($datas, [
                'id_user' => $user->id,
                'id_permission' => $perm->id,
            ]);
        }

        UserPermission::insert($datas);
    }
}
