<?php

namespace App\Http\Controllers\Services;

use App\Models\ExamRevision;
use App\Models\Student;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamRevisionServices extends Helper {
    private static $revisions;

    public static function cache() {
    }

    public static function getAll($id = NULL) {
        static::$revisions = ExamRevision::all();
        if($id){
            return static::$revisions->where('id', $id)->first();
        } else {
            return static::$revisions;
        }
    }

    public static function update($enroll, Request $request) {
    }

    public static function delete($enroll) {
    }

    public static function store(Request $request) {
        $degree = $request->no_correct_answers/($request->no_correct_answers+$request->no_wrong_answers) * 100.00;

        $std = Auth::user();
        if(Static::getAll()->where('student_id', Auth::user()->id)->first()->student) return NULL;

        $std->status = $degree > 50 ? 'semi_accepted' : 'rejected';
        $std->save();

        return ExamRevision::create([
            'exam_id' => $request->exam_id,
            'no_correct_answers' => $request->no_correct_answers,
            'no_wrong_answers' => $request->no_wrong_answers,
            'student_degree' => round($degree, 2),
            'student_id' => Auth::user()->id,
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
