<?php

namespace App\Http\Controllers\Services;

use App\Models\Course;
use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EnrollServices extends Helper {
    private static $enrolls;

    public static function cache() {
    }

    public static function getAll($id = NULL) {
        static::$enrolls = Enroll::with(['course', 'student'])->get();
        if($id){
            return static::$enrolls->where('id', $id)->first();
        } else {
            return static::$enrolls;
        }
    }

    public static function update($enroll, Request $request) {
        static::resetCacheVariable('enrolls');

        $student_id = StudentServices::getStudentByEmail($request->student_email)->id;
        $course_id = CourseServices::getCourseByName($request->course_name)->id;

        $enroll->student_id = $student_id;
        $enroll->course_id = $course_id;

        $enroll->save();

        return $enroll;
    }

    public static function delete($enroll) {
        static::resetCacheVariable('enrolls');
        $enroll->delete();
        return $enroll;
    }

    public static function store($course_name) {
        $course = CourseServices::getCourseByName($course_name);
        if($course)
            return Enroll::create([
                'student_id' => Auth::user()->id,
                'course_id' => $course->id
            ]);
        else
            return NULL;
    }

    public static function validator(Request $request, $type = NULL, $id = NULL) {
        $courses = Static::getStringOfAllRowsOf('CourseServices', 'name');
        $students = Static::getStringOfAllRowsOf('StudentServices', 'email');

        if($type == 'update') {
            // return $request->validate([
            //     'student_email' => "required|in:$students",
            //     'course_name' => "required|in:$courses"
            // ]);

            return $request->validate([
                'course_code' => "required"
            ]);
        } else { // full validation
            return $request->validate([
                'student_email' => "required|in:$students",
                'course_name' => "required|in:$courses"
            ]);
        }
    }
}
