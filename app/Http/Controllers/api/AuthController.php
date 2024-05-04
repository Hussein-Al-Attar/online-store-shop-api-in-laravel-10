<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
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
                return response()->json(['status' => false, 'message' => 'User login successfully',], 401);
            }

            // Authentication successful, retrieve the authenticated user
            $user = Auth::user();


            // Return response with user details and token
            return response()->json([
                'status' => true,
                'message' => 'User login fails',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'token_type' => 'bearer', // Optionally include token type
                    'expires_in' => Auth::factory()->getTTL() * 60, // Optionally include token expiration time
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('User login failed:' . $e->getMessage());

            // Handle any exceptions
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 500);
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
                return response()->json(['status' => false ,'message' => $validator->errors()], 422);
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
                'status' => true,
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
            Log::error('User registration failed:' . $e->getMessage());

            return response()->json(['status' => true, 'message' => 'Unauthorized'], 500);
        }
    }


    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out',
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions
            Log::error('User logout failed:' . $e->getMessage());

            return response()->json(['status' => false, 'message' => 'error in logged out'], 500);
        }
    }

    public function refresh()
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Successfully refresh',
                'user' => Auth::user(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions
            Log::error('User refresh failed:' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'User refresh failed',
            ], 500);
        }
    }

}
