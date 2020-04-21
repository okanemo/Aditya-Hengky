<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use App\Http\Traits\GetApiTrait;

class LoginController extends Controller
{
    use GetAPiTrait;

    function index() {
        return view('login');
    }

    function login(Request $request) {
        try {
            $input = $request->all();
            $user = $this->getApi('post', 'api/login', $input);        
            $user = $user->data;
            session()->put('token', $user->token);
            session()->put('user_id', $user->user->id);
            return redirect('/user/list');        
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $jsonBody = Json_decode((string) $response->getBody());
            return redirect('/login')->with('error', $jsonBody->message);
        }
    }

    function logout() {
        try {
            $user = $this->getApi('get', 'api/logout');        
            \Session::flush();
            return redirect('/');        
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $jsonBody = Json_decode((string) $response->getBody());
            return redirect('/login')->with('error', $jsonBody->message);
        }
    }
}
