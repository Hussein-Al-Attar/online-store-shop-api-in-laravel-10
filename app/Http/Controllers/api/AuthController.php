<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        try {
            // Validate user input
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            // Extract credentials from the request
            $credentials = $request->only('email', 'password');

            // Attempt to authenticate the user
            if (!$token = Auth::attempt($credentials)) {
                // Return unauthorized response if authentication fails
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Authentication successful, retrieve the authenticated user
            $user = Auth::user();


            // Return response with user details and token
            return response()->json([
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'token_type' => 'bearer', // Optionally include token type
                    'expires_in' => Auth::factory()->getTTL() * 60, // Optionally include token expiration time
                ],
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'User registration failed' . $e], 500);
        }
    }



    public function register(Request $request)
    {
        try {
            // Validate request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password), // Hash the password
            ]);

            // Generate JWT token for the newly registered user
            $token = Auth::login($user);

            // Return response with user details and token
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'token_type' => 'bearer', // Optionally include token type
                    'expires_in' => Auth::factory()->getTTL() * 60, // Optionally include token expiration time
                ],
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'User registration failed' . $e], 500);
        }
    }


    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                'message' => 'Successfully logged out',
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'User registration failed' . $e], 500);
        }
    }

    public function refresh()
    {
        try {
            return response()->json([
                'user' => Auth::user(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'User registration failed' . $e], 500);
        }
    }
    public function getUserFromToken(Request $request)
{
    try {
        // Attempt to authenticate the request using the token
        $user = Auth::user();

        // Check if user is authenticated
        if ($user) {
            // User is authenticated, return user details
            return response()->json([
                'user' => $user,
            ]);
        } else {
            // Token is not valid or user is not authenticated
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}
}
