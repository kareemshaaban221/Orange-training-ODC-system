<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Response;
use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use AuthenticatesUsers, Response;

    public function login(Request $request) {
        $this->validateLogin($request);

        $admin = Admin::where('email', $request->email)->first();

        if(!$admin) {
            return $this->notFoundResponse('admin');
        } elseif (!Hash::check($request->password, $admin->password)) {
            return $this->forbiddenResponse('Wrong Password!');
        }

        $token = $admin->createToken('myToken')->plainTextToken;

        return $this->goodResponse([
            'admin' => $admin,
            'token' => $token,
            'message' => 'Loginned Successfully'
        ]);
    }

    public function logout() {
        Auth::user()->tokens()->delete();
        return ['message' => 'Logged Out!'];
    }
}
