<?php

namespace App\Http\Controllers\Services;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CourseServices extends Helper {
    private static $courses;

    public static function cache() {

    }

    public static function getAll($id = NULL) {
        static::$courses = Course::with('category')->get();
        if($id){
            return static::$courses->where('id', $id)->first();
        } else {
            return static::$courses;
        }
    }

    public static function update($course, Request $request) {
        // static::resetCacheVariable('courses');

        // $id = CategoryServices::getCategoryByName($request->category_name)->id;

        // $course->name = $request->name;
        // $course->level = $request->level;
        // $course->category_id = $id;
        // $course->save();

        // return $course;
    }

    public static function delete($course) {
        // static::resetCacheVariable('courses');
        // $course->delete();
        // return $course;
    }

    public static function store(Request $request) {
        // static::resetCacheVariable('courses');

        // $id = CategoryServices::getCategoryByName($request->category_name)->id;

        // return Course::create([
        //     'name' => $request->name,
        //     'level' => $request->level,
        //     'category_id' => $id,
        // ]);
    }

    public static function validator(Request $request, $type = NULL, $id = NULL) {
        // $str = Static::getStringOfAllRowsOf(CategoryServices::class, 'name');

        // if($type == 'update') {
        //     return $request->validate([
        //         'name' => "required|string|max:255|unique:courses,name,$id",
        //         'level' => 'required|in:entry,beginner,intermediate,advanced',
        //         'category_name' => "required|in:$str"
        //     ]);
        // } else { // full validation
        //     return $request->validate([
        //         'name' => "required|string|max:255|unique:courses,name",
        //         'level' => 'required|in:entry,beginner,intermediate,advanced',
        //         'category_name' => "required|in:$str"
        //     ]);
        // }
    }

    public static function getCourseByName($course_name) {
        return CourseServices::getAll()
            ->where('name', $course_name)
            ->first();
    }
}
