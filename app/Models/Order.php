<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable=[
        'old_id', 'bkg_date', 'bkg_no', 'bl_no', 'bkg_type', 'month', 'month_no', 'tag', 'company_name', 'short_name', 'zip_code',
        'address', 'header', 'mobile', 'legal_number', 'carrier', 'c_staff', 'service', 'vessel_name', 'voyage', 'loading_country_id',
        'loading_country_name', 'loading_port_id', 'loading_port_name', 'etd', 'cy_open', 'cy_cut', 'doc_cut', 'delivery_country_id',
        'delivery_country_name', 'delivery_port_id', 'delivery_port_name', 'eta', 'free_time_dem', 'free_time_det', 'discharge_country',
        'discharge_port', 'remark', 'creator',
    ];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
