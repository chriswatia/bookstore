<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validation logic
        $rules = array(
            'name'   => 'required',
			'email'   => 'required|email|unique:users,email',
			'password' => 'required',
		);

        $validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}

        $role = Role::where('name', 'User')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $role->id
        ]);

        $token = $user->createToken('BookStore')->accessToken;

        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request)
    {
        // Validation logic
        $rules = array(
			'email'   => 'required',
			'password' => 'required',
		);

        $validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = auth()->user()->createToken('BookStore')->accessToken;
            $user = Auth::user();
            $user->role = Auth::user()->role->name;
            return response()->json(['token' => $token, 'user' => $user], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}


