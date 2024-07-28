<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderOperateLog extends Model
{
    const TYPE_MAIL = 1; //日志类型 发送邮件
    const TYPE_NODE_CONFIRM = 2; //日志类型 节点确认
    const TYPE_NODE_TOP = 3; //日志类型 取消置顶事件
    const TYPE_CHANGE_ORDER = 4; //日志类型 改单申请
    protected $guarded = [];

    static public function writeLog($order_id, $type, $content = '')
    {
        self::query()->create([
            'order_id' => $order_id,
            'type' => $type,
            'operator' => auth('user')->user()->name,
            'operate_at' => date('Y-m-d H:i:s'),
            'content' => $content
        ]);
    }
}
