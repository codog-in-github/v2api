<?php

namespace App\Listeners;

use App\Events\OrderNodeChange;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckFinishWhenNodeChange
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderNodeChange  $event
     * @return void
     */
    public function handle(OrderNodeChange $event)
    {
        $node = $event->node;
        $order = $node->order;
        if(!in_array($order->status, [Order::STATUS_IN_DO, Order::STATUS_FINISH])) {
            return;
        }
        if($node->is_enable == 1 && $node->is_confirm == 0){
            $order->status = Order::STATUS_IN_DO;
            $order->save();
            return;
        }
        $orderNodes = $order->nodes;
        $isFinish = $orderNodes->every(function ($item) {
            return $item->is_enable == 0 || $item->is_confirm == 1;
        });
        if($isFinish) {
            $order->status = Order::STATUS_FINISH;
            $order->finish_at = now();
        } else {
            $order->status = Order::STATUS_IN_DO;
        }
        $order->save();
    }
}
