<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

abstract class Helper {

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
