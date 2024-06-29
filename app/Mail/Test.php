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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $file)
    {
        $this->order = $order;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        Mail::to('1135894621@qq.com')->send(new Test(123, 'file/20240629/R0004872.JPG'));
        return $this->view('emails.test')
            ->attachFromStorageDisk('public', $this->file)
            ->with([
                'order' => $this->order
            ]);
    }
}
