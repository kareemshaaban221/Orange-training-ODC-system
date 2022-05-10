<?php

namespace App\Http\Controllers\Services;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StudentServices extends Helper {

    private static $students;

    public static function cache() {
        static::$students = Cache::remember('students', 24*60*60, fn () => Student::all());
    }

    public static function getAll($id = NULL) {
        if($id){
            return static::$students->where('id', $id)->first();
        } else {
            return static::$students;
        }
    }

    public static function update($student, Request $request) {
        // static::resetCacheVariable('students');

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->address = $request->address;
        $student->college_name = $request->college_name;
        $student->save();

        return $student;
    }

    public static function updatePassword($student, Request $request) {
        // static::resetCacheVariable('students');

        $student->password = bcrypt($request->password);
        $student->save();
    }

    public static function delete($student) {
        // static::resetCacheVariable('students');
        $student->delete();
        return $student;
    }

    public static function store(Request $request) {
        // static::resetCacheVariable('students');

        return Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'college_name' => $request->college_name,
            'status' => $request->status
        ]);
    }

    public static function validator(Request $request, $type = NULL, $id = NULL) {
        if($type == 'update') {
            return $request->validate([
                'email' => "required|string|max:255|email|unique:students,email,$id",
                'name' => 'required|string|max:255',
                'phone' => "required|string|digits:11|starts_with:010,011,012,015|unique:students,phone,$id",
                'address' => 'required|string|max:255',
                'college_name' => 'required|string|max:50'
            ]);
        } elseif ($type = 'password') {
            return $request->validate([
                'password' => 'required|string|min:8|max:255|confirmed'
            ]);
        } else { // full validation
            return $request->validate([
                'email' => "required|string|max:255|email|unique:admins,email",
                'name' => 'required|string|max:255',
                'phone' => 'required|string|digits:11|starts_with:010,011,012,015',
                'address' => 'required|string|max:255',
                'college_name' => 'required|string|max:50',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'required|string|in:pending,semi_accepted,accepted,rejected',
            ]);
        }
    }

    public static function getStudentByEmail($student_email) {
        return StudentServices::getAll()
            ->where('name', $student_email)
            ->first();
    }
}
