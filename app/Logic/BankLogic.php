<?php

namespace App\Logic;
use App\Models\Bank;

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
}
