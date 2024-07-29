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
    public $mailFrom;
    public $mailName;
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
        $this->mailFrom = $from;
        $this->mailName = $name;
        $this->files = $files;
    }

    /**
     * Build the message.  /用自定义邮件内容发送邮件
     *
     * @return $this
     */
    public function build()
    {
        $files = is_array($this->files) ? $this->files : implode(',', $this->files);
        foreach ($files as $file){
            $this->attach(formatFile($file));
        }
        return $this->text('emails.custom')
            ->from($this->mailFrom, $this->mailName)
            ->subject($this->subject)
            ->with(['content' => $this->content]);
    }
}
