<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestBookDetail extends Model
{
    use SoftDeletes;
    protected $fillable = ['request_id', 'type', 'item_name', 'detail', 'currency', 'price', 'num', 'unit', 'tax', 'amount'];
}
