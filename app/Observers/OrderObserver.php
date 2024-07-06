<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    public function updated(Order $order)
    {
        if ($order->isDirty('remark')){
            $order->remark .= '-' . auth('user')->user()->username . ' ' . date('Ymd h:i:s');
        }
    }
}
