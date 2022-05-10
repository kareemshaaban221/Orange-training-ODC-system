<?php

namespace App\Http\Controllers\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryServices extends Helper {
    private static $categories;

    public static function cache() {
        static::$categories = Cache::remember('categories', 24*60*60, fn () => Category::all());
    }

    public static function getAll($id = NULL) {
        if(!static::$categories)    static::cache();

        if($id){
            return static::$categories->where('id', $id)->first();
        } else {
            return static::$categories;
        }
    }

    public static function update($category, Request $request) {
        static::resetCacheVariable('categories');

        $category->name = $request->name;
        $category->save();

        return $category;
    }

    public static function delete($category) {
        static::resetCacheVariable('categories');
        $category->delete();
        return $category;
    }

    public static function store(Request $request) {
        static::resetCacheVariable('categories');

        return Category::create([
            'name' => $request->name
        ]);
    }

    public static function validator(Request $request, $type = NULL, $id = NULL) {
        if($type == 'update') {
            return $request->validate([
                'name' => "required|string|max:255|unique:categories,name,$id",
            ]);
        } else { // full validation
            return $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
            ]);
        }
    }

    public static function getCategoryByName($category_name) {
        return CategoryServices::getAll()
            ->where('name', $category_name)
            ->first();
    }
}
