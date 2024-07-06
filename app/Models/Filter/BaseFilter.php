<?php
namespace App\Models\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class BaseFilter
{

    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {

            if (method_exists($this, $name)) {
                call_user_func_array([$this, $name], array_filter([$value], function ($item){
                    return !is_null($item);
                }));
            }
        }

        return $this->builder;
    }

    public function filters()
    {
        return $this->request->all();
    }
}
