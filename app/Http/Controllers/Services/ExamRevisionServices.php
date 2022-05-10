<?php

namespace App\Http\Controllers\Services;

use App\Models\Course;
use App\Models\Enroll;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ExamRevisionServices extends Helper {
    private static $exam_revisions;

    public static function cache() {
        static::$exam_revisions = Cache::remember('exam_revisions', 24*60*60, fn () => ExamQuestion::all());
    }

    public static function getAll($id = NULL) {
        if($id){
            return static::$exam_revisions->where('id', $id)->first();
        } else {
            return static::$exam_revisions;
        }
    }
}
