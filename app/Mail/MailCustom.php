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
    public $files;

    /**
     * @param string $subject
     * @param string $content
     * @param string $from
     * @param array $files
     */
    public function __construct(string $subject, string $content, string $from, string $name, array $files = [])
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->from = $from;
        $this->name = $name;
        $this->files = $files;
    }

    /**
     * Build the message.  /用自定义邮件内容发送邮件
     *
     * @return $this
     */
    public function build()
    {
        foreach ($this->files as $file){
            $this->attach(formatFile($file));
        }
        return $this->text('emails.custom')
            ->from($this->from, $this->name)
            ->subject($this->subject)
            ->with(['content' => $this->content]);
    }
}
