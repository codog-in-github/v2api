<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class Test extends Mailable
{
    use Queueable, SerializesModels;

    public $order = 1231312;
    public $file;
    public $from;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($from, $order, $file)
    {
        $this->order = $order;
        $this->file = $file;
        $this->from = $from;
    }

    /**
     * Build the message.  /用预定义邮件模板发送邮件
     *
     * @return $this
     */
    public function build()
    {
//        Mail::to('1135894621@qq.com')->send(new Test('13105592748@163.com', 123, 'file/20240629/R0004872.JPG'));
        return $this->from($this->from)
            ->text()
            ->view('emails.test')
            ->attachFromStorageDisk('public', $this->file)
            ->with([
                'order' => $this->order
            ]);
    }
}
