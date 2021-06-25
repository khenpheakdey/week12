<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $newUserData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        $newUserData['password'] = bcrypt($newUserData['password']);
        $newUser = User::create($newUserData);
        $accessToken  = $newUser->createToken('authToken')->accessToken;
        return response([
            'user' => $newUser,
            'access_token' => $accessToken
        ]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($loginData)) {
            $accessToken = auth()->user()->createToken("authToken")->accessToken;
            return response([
                "user" => auth()->user(),
                "access_token" => $accessToken
            ]);
        } else {
            return response([
                "error" => "wrong"
            ]);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json([
            "status" => true,
            "success" => "Logged out!"
        ]);
    }
}
