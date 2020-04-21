<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\UserPermission;
use App\Permission;
use App\OauthAccessToken;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Collection;
use DB;
use App\Http\Controllers\API\BaseController;

class UserController extends BaseController
{
  public function login(){
      if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
          $user = Auth::user();
          $permission = UserPermission::with('permission')->where('id_user', $user->id)->get();
          $permission = collect($permission->toArray())->pluck('permission.name')->all();
          $token =  $user->createToken('nApp', $permission)->accessToken;
          return $this->sendResponse(['user' => $user, 'token' => $token ], 'success');
      }
      else{
          return $this->sendError('Unauthorized', [], 403);
      }
  }

    public function index() {
        $users = User::get()->where('id', '!=', 1);
        return $this->sendResponse($users, 'success');
    }

    public function get(Request $request,$id) {
        $user = User::where('id', $id)->with(['user_permission' => function ($q) {
            $q->with('permission');
        }])->first();
        if (!$user || $user->id == 1) {
            return $this->sendError('Not Found', [], 404);
        }
        return $this->sendResponse($user, 'success');    
    }

    public function post(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }
        
        try {
            DB::beginTransaction();
            $input = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ];
            $user = User::create($input);
            $arrPermission = [];
            foreach($request->permission as $perm) {
                array_push($arrPermission, [
                    'id_user' => $user->id,
                    'id_permission' => $perm,
                ]);
            }
            UserPermission::insert($arrPermission);
            DB::commit();
            return $this->sendResponse([], 'success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }

    public function put(Request $request, $id) {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return $this->sendError('Not Found', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }
        try {
            DB::beginTransaction();
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password) {
                $user->password =  bcrypt($request->password);
            }
            $user->save();
            UserPermission::where('id_user', $id)->delete();
            $permission = Permission::whereIn('id', $request->permission)->get();
            $arrPermission = [];
            $scopes = [];
            foreach($permission as $perm) {
                array_push($arrPermission, [
                    'id_user' => $user->id,
                    'id_permission' => $perm->id,
                ]);
                array_push($scopes, $perm->name);
            }
            UserPermission::insert($arrPermission);
            OauthAccessToken::where('user_id', $id)->update(['scopes' => $scopes]);
            DB::commit();
            return $this->sendResponse([], 'success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }

    public function delete($id)
    {
        $user = User::where('id', $id)->first();
        if (!$user || $user->id == 1) {
            return $this->sendError('Not Found', [], 404);
        }
        $user->delete();
        return $this->sendResponse([], 'success');
    } 

    public function logout(){ 
        if (Auth::check()) {
            Auth::user()->accessTokens()->delete();
        }
        return $this->sendResponse([], 'success');
    }
}
