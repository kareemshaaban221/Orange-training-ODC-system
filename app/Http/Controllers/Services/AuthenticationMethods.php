<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;

trait AuthenticationMethods {

    protected function validateRegister(Request $request) {
        $request->validate([
            'email' => "required|string|max:255|email|unique:students,email",
            'name' => 'required|string|max:255',
            'phone' => 'required|string|digits:11|starts_with:010,011,012,015|unique:students,phone',
            'address' => 'required|string|max:255',
            'college_name' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed|max:255',
        ]);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);
    }
}

