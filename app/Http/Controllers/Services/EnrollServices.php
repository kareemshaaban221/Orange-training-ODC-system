<?php

namespace App\Http\Controllers\Services;

use App\Models\Course;
use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EnrollServices extends Helper {
    private static $enrolls;

    public static function cache() {
        static::$enrolls = Cache::remember('enrolls', 24*60*60, fn () => Enroll::all());
    }

    public static function getAll($id = NULL) {
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

    public static function store(Request $request) {
        static::resetCacheVariable('enrolls');

        $std_id = StudentServices::getStudentByEmail($request->student_email)->id;
        $course_id = CourseServices::getCourseByName($request->course_name)->id;

        return Enroll::create([
            'student_id' => $std_id,
            'course_id' => $course_id
        ]);
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
