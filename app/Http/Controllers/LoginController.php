<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller {

    /**
     * @param Request $request
     * @return array
     */
    public function login(Request $request): array {
        if ($request->has('token') && $request->get('token') == Env::get('TOKEN')) {
            Session::put('login', true);
            return ['code' => 0, 'msg' => 'success'];
        } else {
            return ['code' => 500, 'msg' => 'failed'];
        }
    }

    /**
     * @return array
     */
    public function logout(): array {
        Session::remove('login');
        return ['code' => 0, 'msg' => 'success'];
    }

    /**
     * @return array
     */
    public function login_status(): array {
        if (Session::has('login') && Session::get('login')) {
            return ['code' => 0, 'msg' => 'Logged'];
        } else {
            return ['code' => 500, 'msg' => 'Not Logged'];
        }
    }
}
