<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(ContainerDetail::class, 'container_id', 'id');
    }
}
