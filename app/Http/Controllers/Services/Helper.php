<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

abstract class Helper {
    
    public static abstract function cache();

    public static abstract function getAll($id = NULL);

    public static abstract function update($user, Request $request);

    public static abstract function delete($user);

    public static abstract function validator(Request $request, $opType);

    public static function resetCacheVariable($var) {
        Cache::put($var, NULL);
    }

    public static function getStringOfAllRowsOf($service, $var) {
        $entity = $service::getAll();

        if(!$entity) {
            $service::cache();
            $entity = $service::getAll();
        }

        $str = '';
        foreach($entity as $e) {
            $str .= $e->$var . ',';
        }

        return $str;
    }

    public static function getArrayOfAllRowsOf($service, $var) {
        $entity = $service::getAll();

        if(!$entity) {
            $service::cache();
            $entity = $service::getAll();
        }

        $arr[] = [];
        foreach($entity as $e) {
            $arr[] = $e->$var;
        }

        return $arr;
    }
}
