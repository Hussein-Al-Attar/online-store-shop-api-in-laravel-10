<?php

namespace App\Http\Controllers\api;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6',
        ]);

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => isset($request->password) ? bcrypt($request->password) : $user->password,
        ]);

        return response()->json($user, 200);
    }


    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
