<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email,'. $user->id,
            'status' => 'required|in:active,inactive',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $user->update($request->all());
            return response()->json(['status' => 'success', 'user' => $user], 200);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->posts()->delete();
        $user->delete();
        return response()->json(['message' => 'User Deleted successfully']);
    }
}
