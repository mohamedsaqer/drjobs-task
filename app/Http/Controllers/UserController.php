<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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

    public function show(User $user)
    {
        return $user;
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $this->authorize('update', $user);
        $user->update($request->validated());
        return response()->json(['status' => 'success', 'user' => $user], 200);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        // we can use soft delete
        $user->posts()->delete();
        $user->delete();
        return response()->json(['message' => 'User Deleted successfully'], 200);
    }
}
