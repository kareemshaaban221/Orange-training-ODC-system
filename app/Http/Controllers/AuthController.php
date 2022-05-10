<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Services\AuthenticationMethods;
use App\Http\Controllers\Services\Response;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use AuthenticationMethods, Response;

    public function register(Request $request) {
        $this->validateRegister($request);

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'college_name' => $request->college_name,
            'status' => 'pending'
        ]);

        $token = $student->createToken('myToken')->plainTextToken;

        return $this->createdResponse([
            'student' => $student,
            'token' => $token
        ], 'student', 'Registered Successfully');
    }

    public function login(Request $request) {
        $this->validateLogin($request);

        $student = Student::where('email', $request->email)->first();

        if(!$student) {
            return $this->notFoundResponse('student');
        } elseif (!Hash::check($request->password, $student->password)) {
            return $this->forbiddenResponse('Wrong Password!');
        }

        $token = $student->createToken('myToken')->plainTextToken;

        return $this->goodResponse([
            'student' => $student,
            'token' => $token,
            'message' => 'Loginned Successfully'
        ]);
    }

    public function logout() {
        Auth::user()->tokens()->delete();
        return $this->resetContentResponse([], 'Logged Out!');
    }
}
