<?php

namespace App\Logic;
use App\Models\Customer;

class CustomerLogic extends Logic
{
    public static function list($request)
    {
        $query = Customer::query();
        if ($request['header']){
            $query->where('header', 'like', "%{$request['header']}%");
        }
        if ($request['mobile']){
            $query->where('mobile', 'like', "%{$request['mobile']}%");
        }
        if ($request['company_name']){
            $query->where('company_name', 'like', "%{$request['company_name']}%");
        }
        return $query->get();
    }

    public static function save($request)
    {
       return Customer::query()->findOrNew($request['id'] ?? 0)->fill($request->all())->save();
    }

    public static function delete($request)
    {
        return Customer::query()->where('id', $request['id'])->delete();
    }
}
