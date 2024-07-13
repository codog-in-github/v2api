<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use  SoftDeletes;
    protected $fillable = [
        'company_name', 'short_name', 'zip_code', 'address', 'header', 'mobile', 'legal_number', 'email'
    ];
}
