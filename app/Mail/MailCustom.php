<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailCustom extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $from;
    public $name;
    public $file;

    /**
     * @param $subject
     * @param $content
     * @param $from
     * @param $file
     */
    public function __construct($subject, $content, $from, $name, $file)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->from = $from;
        $this->name = $name;
        $this->file = $file;
    }

    /**
     * Build the message.  /用自定义邮件内容发送邮件
     *
     * @return $this
     */
    public function build()
    {
        Mail::to('recipient@example.com')->send(new MailCustom('邮件主题', '邮件内容', '', ''));
        return $this->text('emails.custom')
            ->from($this->from, $this->name)
            ->subject($this->subject)
            ->with(['content' => $this->content])
            ->attachFromStorageDisk('public', $this->file);
    }
}
