<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            ['name' => 'user.create'],
            ['name' => 'user.list'],
            ['name' => 'user.edit'],
            ['name' => 'user.delete'],
        ];
        Permission::insert($datas);
    }
}
