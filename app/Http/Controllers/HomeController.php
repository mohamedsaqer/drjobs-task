<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard(Request $request)
    {
        if (Auth::user()->role == 'admin') {
            $users = User::where('role', 'user')->paginate(10);
            return view('dashboard', compact('users'));
        } else {
            $posts = Post::with('postCategory')->where('user_id', Auth::id())->paginate(30);
            if($request->ajax()){
                $limit = request()->has('limit') ? request('limit') : 30;
                $page = request()->has('page') ? request('page') : 1;
                return Post::with('postCategory')->where('user_id', Auth::id())->paginate($limit);
            }
            return view('dashboard', compact('posts'));
        }
    }
}
