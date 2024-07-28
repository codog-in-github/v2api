<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestBookCount extends Model
{
    protected $fillable = ['request_id', 'type', 'item_name', 'item_amount', 'purchase'];
}
