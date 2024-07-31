<?php

namespace App\Models;

use App\Exceptions\ErrorException;
use App\Utils\StringRender;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Blade;

class MailTemplate extends Model
{
    protected $guarded = [];

    static public function render($template, $nodeId, $orderId)
    {
        $order = Order::query()->with(['containers', 'containers.details', 'carrier', 'customCom'])->find($orderId);
        try {
            $subject = Blade::render($template->subject, ['order' => $order]);
            $content = Blade::render($template->content, ['order' => $order]);
            return compact('subject', 'content');
        }catch (\Exception $e){
            throw new ErrorException($e->getMessage());
        }

    }
}
