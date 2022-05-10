<?php

namespace App\Http\Controllers\Services;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminServices extends Helper {
    private static $admins;

    public static function cache() {
        static::$admins = Cache::remember('admins', 24*60*60, fn () => Admin::all());
    }

    public static function getAll($id = NULL) {
        if($id){
            return static::$admins->where('id', $id)->first();
        } else {
            return static::$admins;
        }
    }

    public static function show($id) {
        return static::getAll($id);
    }

    public static function update($admin, Request $request) {
        static::resetCacheVariable('admins');

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->role = $request->role;
        $admin->save();

        return $admin;
    }

    public static function delete($admin) {
        static::resetCacheVariable('admins');
        $admin->delete();
        return $admin;
    }

    public static function store(Request $request) {
        static::resetCacheVariable('admins');

        return Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'sub_admin',
            'image' => $request->image
        ]);
    }

    public static function validator(Request $request, $type = NULL, $id = NULL) {
        if($type == 'update') {
            return $request->validate([
                'email' => "required|string|email|max:255|unique:admins,email,$id",
                'name' => 'required|string|max:255|',
                'role' => 'required|max:255|in:admin,sub_admin'
            ]);
        } else { // full validation
            return $request->validate([
                'email' => 'required|string|max:255|email|unique:admins,email',
                'name' => 'required|string|max:255|max:40',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,sub_admin',
                'image' => 'string|max:255'
            ]);
        }
    }
}
