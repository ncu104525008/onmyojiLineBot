<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

use App\User;

class UsersController extends Controller
{
    public function login()
    {
        $account = Input::get('account');
        $password = Input::get('password');

        $count = User::where('account', '=', $account)->count();
        if ($count > 0)
        {
            $user = User::where('account', '=', $account)->first();

            if ($user->password == md5($password))
            {
                Session::put('id', $user->id);
                Session::save();

                return 'success';
            }
            else
            {
                return 'fail';
            }
        }
        else
        {
            return 'fail';
        }
    }
}
