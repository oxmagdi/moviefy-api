<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{



    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name'     => $fields['name'],
            'email'    => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('my_aswome-token')->plainTextToken;

        return response([
            'user'  => $user,
            'token' => $token
        ], 201);
    }


    public function login(Request $request){
        $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('my_aswome-token')->plainTextToken;

        return response([
            'user'  => $user,
            'token' => $token
        ], 200);
    }


    public function logout(Request $request){
        $result = auth()->user()->tokens()->delete();

        return [
            'result' => $result,
            'message' => 'Logged out!'
        ];
    }
}
