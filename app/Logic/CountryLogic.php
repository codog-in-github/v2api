<?php

namespace App\Logic;
use App\Models\Bank;
use App\Models\Country;

class CountryLogic extends Logic
{
    public static function list($request)
    {
        $query = Country::query();
        if ($request['pid']){
            $query->where('pid', $request['pid']);
        }
        return $query->get();
    }

    public static function getTree($request)
    {
        $query = Country::query();
        if ($request['pid']){
            $query->where('pid', $request['pid']);
        }
        $list = $query->get()->keyBy('id')->toArray();
        return getTree($list);
    }
}
