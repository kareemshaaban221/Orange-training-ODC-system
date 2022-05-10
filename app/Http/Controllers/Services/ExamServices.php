<?php

namespace App\Http\Controllers\Services;

use App\Models\Course;
use App\Models\Enroll;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ExamServices extends Helper {
    private static $exams;

    public static function cache() {
        static::$exams = Cache::remember('exams', 24*60*60, fn () => Exam::all());
    }

    public static function getAll($id = NULL) {
        if($id){
            return static::$exams->where('id', $id)->first();
        } else {
            return static::$exams;
        }
    }

    public static function update($exam, Request $request) {
        static::resetCacheVariable('exams');

        $student_id = StudentServices::getStudentByEmail($request->student_email)->id;
        $course_id = CourseServices::getCourseByName($request->course_name)->id;

        $exam->student_id = $student_id;
        $exam->course_id = $course_id;

        $exam->save();

        return $exam;
    }

    public static function delete($exam) {
        static::resetCacheVariable('exams');
        $exam->delete();
        return $exam;
    }

    public static function store(Request $request) {
        static::resetCacheVariable('exams');

        $course_id = CourseServices::getCourseByName($request->course_name)->id;

        return Exam::create([
            'course_id' => $course_id,
            'duration' => $request->duration
        ]);
    }

    public static function validator(Request $request, $type = NULL, $id = NULL) {
        $courses = Static::getStringOfAllRowsOf(CourseServices::class, 'name');

        if($type == 'update') {
            return $request->validate([
                'course_name' => "required|in:$courses",
                'duration' => 'required|numeric|max:360'
            ]);
        } else { // full validation
            return $request->validate([
                'course_name' => "required|in:$courses",
                'duration' => 'required|integer|max:360'
            ]);
        }
    }
}
