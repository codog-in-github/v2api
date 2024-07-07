<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestBookExtra extends Model
{
    use SoftDeletes;
    protected $fillable = ['request_id', 'column', 'value'];
}
