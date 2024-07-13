<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestBook extends Model
{
    use SoftDeletes;
    const TYPE_NORMAL = 1;
    const TYPE_PLACE = 2;
    protected $fillable = ['order_id', 'type', 'no', 'date', 'zip_code', 'company_name', 'company_address', 'total_amount', 'tax',
        'request_amount', 'bank', 'address', 'is_stamp', 'is_entry', 'is_confirm'];

    public function details()
    {
        return $this->hasMany(RequestBookDetail::class, 'request_id', 'id');
    }

    public function counts()
    {
        return $this->hasMany(RequestBookCount::class, 'request_id', 'id');
    }

    public function extras()
    {
        return $this->hasMany(RequestBookExtra::class, 'request_id', 'id');
    }
}
