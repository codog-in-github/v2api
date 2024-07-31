<?php

namespace App\Models;

use App\Utils\StringRender;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    protected $guarded = [];

    static public function render($template, $nodeId, $orderId)
    {
        $order = Order::query()->with(['containers', 'containers.details',  'customCom'])->find($orderId);
        $subject = app(StringRender::class)->renderString($template->subject, ['order' => $order]);
        dd($subject);
//        $content = app(StringRender::class)->renderString($template->content, ['order' => $order]);
//        return compact('subject', 'content');
    }
}
