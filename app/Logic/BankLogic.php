<?php

namespace App\Logic;
use App\Models\Bank;
use App\Models\Department;
use App\Models\Option;
use App\Models\Select;

class BankLogic extends Logic
{
    public static function list($request)
    {
        $query = Bank::query();
        if ($request['name']){
            $query->where('name', 'like', "%{$request['name']}%");
        }
        return $query->get();
    }

    public static function selectList($request)
    {
        $query = Select::query();
        if ($request['name']){
            $query->where('name', 'like', "%{$request['name']}%");
        }
        if ($request['type']){
            $query->where('type', $request['type']);
        }
        return $query->get();
    }

    public static function optionList($request)
    {
        $query = Option::query();
        if ($request['value']){
            $query->where('value', 'like', "%{$request['value']}%");
        }
        if ($request['select_id']){
            $query->where('select_id', $request['select_id']);
        }
        return $query->get();
    }
    public static function departmentList($request)
    {
        $query = Department::query();
        if ($request['name']){
            $query->where('name', 'like', "%{$request['name']}%");
        }
        return $query->get();
    }


}
