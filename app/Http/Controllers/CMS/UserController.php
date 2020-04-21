<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use App\Http\Traits\GetApiTrait;
use Illuminate\Support\Collection;


class UserController extends Controller
{
    use GetApiTrait;

    function __construct() {
        $this->middleware(function ($request, $next) {
            if (!session()->get('token')) {
                return redirect('/');
            }
            return $next($request);
        }); 
    }

    function index() {
        try {
            $users = $this->getApi('get', 'api/user');
            $users = $users->data;
            $role = $this->getApi('get', 'api/permission/list/'. session()->get('user_id'));
            $role = $role->data;
            return view('user-list', compact('role', 'users'));    
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $jsonBody = Json_decode((string) $response->getBody());
            if ($response->getStatusCode() == 403) {
                return abort(response('Forbiden', 403));
            }
            throw new \Exception($jsonBody->message);
        }
    }

    function create() {
        try {
            $permission = $this->getApi('get', 'api/permission/list/');
            $permission = $permission->data;
            $role = $this->getApi('get', 'api/permission/list/'. session()->get('user_id'));
            $role = $role->data;            
            return view('create', compact('role', 'permission'));    
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $jsonBody = Json_decode((string) $response->getBody());
            if ($response->getStatusCode() == 403) {
                return abort(response('Forbiden', 403));
            }
            throw new \Exception($jsonBody->message);
        }
    }

    function post(Request $request) {
        try {
            $input = $request->all();
            $create = $this->getApi('post', 'api/user', $input);
            return redirect('/user/list'); 
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $jsonBody = json_decode((string) $response->getBody());
            if ($response->getStatusCode() == 403) {
                return abort(response('Forbiden', 403));
            }
            if ($response->getStatusCode() == 400) {
                return redirect('/user/create')->withErrors($jsonBody->data);
            }
            return redirect('/user/create')->with('error', $jsonBody->message);
        }
    }

    function get($id) {
        try {
            $user = $this->getApi('get', 'api/user/'. $id);
            $user = $user->data;
            $userPermission = collect($user->user_permission)->pluck('id_permission')->all();
            $permission = $this->getApi('get', 'api/permission/list/');
            $permission = $permission->data;
            $role = $this->getApi('get', 'api/permission/list/'. session()->get('user_id'));
            $role = $role->data;  
            return view('edit', compact('user', 'role', 'permission', 'userPermission')); 
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $jsonBody = json_decode((string) $response->getBody());
            if ($response->getStatusCode() == 403) {
                return abort(response('Forbiden', 403));
            }
            if ($response->getStatusCode() == 404) {
                return abort_404();
            }
            throw new \Exception($jsonBody->message);
        }
    }

    function put(Request $request, $id) {
        try {
            $input = $request->all();
            $update = $this->getApi('put', 'api/user/'. $id, $input);
            return redirect('/user/list'); 
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $jsonBody = json_decode((string) $response->getBody());
            if ($response->getStatusCode() == 403) {
                return abort(response('Forbiden', 403));
            }
            if ($response->getStatusCode() == 400) {
                return redirect('/user/edit/'. $id)->withErrors($jsonBody->data);
            }
            if ($response->getStatusCode() == 404) {
                return abort_404();
            }
            return redirect('/user/create')->with('error', $jsonBody->message);
        }
    }

    function delete($id) {
        try {
            $delete = $this->getApi('delete', 'api/user/'. $id);
            return redirect('user/list');
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $jsonBody = json_decode((string) $response->getBody());
            if ($response->getStatusCode() == 403) {
                return abort(response('Forbiden', 403));
            }
            throw new \Exception($jsonBody->message);
        }
    }
}
