<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',

        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        } else {
            if (Auth::attempt($request->only(["email", "password"]))) {
                if (Auth::user()->status == 'active') {
                    return response()->json(['status' => 'success', 'redirect_to' => 'dashboard'], 200);
                } else {
                    Auth::logout();
                    return response()->json([["this user cant login until admin active his account"]], 404);
                }
            } else {
                return response()->json([["Invalid credentials"]], 404);
            }
        }
    }
}
