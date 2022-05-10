<?php

namespace App\Http\Controllers\Services;

use App\Models\Course;
use App\Models\Enroll;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ExamQuestionServices extends Helper {
    private static $questions;

    public static function cache() {
        static::$questions = Cache::remember('questions', 24*60*60, fn () => ExamQuestion::all());
    }

    public static function getAll($id = NULL) {
        if($id){
            return static::$questions->where('id', $id)->first();
        } else {
            return static::$questions;
        }
    }

    public static function update($question, Request $request) {
        static::resetCacheVariable('questions');

        $student_id = StudentServices::getStudentByEmail($request->student_email)->id;
        $course_id = CourseServices::getCourseByName($request->course_name)->id;

        $question->student_id = $student_id;
        $question->course_id = $course_id;

        $question->save();

        return $question;
    }

    public static function delete($question) {
        static::resetCacheVariable('questions');
        $question->delete();
        return $question;
    }

    public static function store(Request $request, $id) {
        static::resetCacheVariable('questions');

        return ExamQuestion::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'fake_answer_1' => $request->fake_answer_1,
            'fake_answer_2' => $request->fake_answer_2,
            'fake_answer_3' => $request->fake_answer_3,
            'exam_id' => $id,
        ]);
    }

    public static function validator(Request $request, $type = NULL, $id = NULL) {

        if($type == 'update') {
            return $request->validate([
                'question' => "required|string|max:255",
                'answer' => "required|string|max:255",
                'fake_answer_1' => "required|string|max:255",
                'fake_answer_2' => "required|string|max:255",
                'fake_answer_3' => "required|string|max:255",
            ]);
        } else { // full validation
            return $request->validate([
                'question' => "required|string|max:255",
                'answer' => "required|string|max:255",
                'fake_answer_1' => "required|string|max:255",
                'fake_answer_2' => "required|string|max:255",
                'fake_answer_3' => "required|string|max:255",
            ]);
        }
    }
}
