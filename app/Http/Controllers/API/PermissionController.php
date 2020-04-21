<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Permission;
use App\UserPermission;
use Illuminate\Support\Collection;
use App\Http\Controllers\API\BaseController;

class PermissionController extends BaseController
{
    public function index(){
        $permission = Permission::get();
        return $this->sendResponse($permission, 'success');
    }

    public function get($id){
        $permission = UserPermission::where('id_user', $id)->with('permission')->get();
        $permission = collect($permission->toArray())->pluck('permission.name')->all();
        return $this->sendResponse($permission, 'success');
    }
}
