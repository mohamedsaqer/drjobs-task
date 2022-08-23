<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',   // required and email format validation
            'password' => 'required', // required and number field validation

        ]); // create the validations
        if ($validator->fails())   //check all validations are fine, if not then redirect and show error messages
        {
            return response()->json($validator->errors(), 422);
            // validation failed return with 422 status

        } else {
            //validations are passed try login using laravel auth attemp
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

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:8',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['status' => 'success', 'redirect_to' => 'login'], 200);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect('login');
    }

    public function dashboard(Request $request)
    {
        if (Auth::user()->role == 'admin') {
            $users = User::where('role', 'user')->paginate(10);
            return view('dashboard', compact('users'));
        } else {
            $posts = Post::with('postCategory')->where('user_id', Auth::id())->paginate(30);
            if($request->ajax()){
                return view('dashboard',['tables'=>$posts]);
            }
            return view('dashboard', compact('posts'));
        }
    }
}
