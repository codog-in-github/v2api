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
        $this->files = array_map([\App\Utils\Order\OrderFiles::getInstance(), 'tryToFilePath'], $files);
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
            $this->attach($file);
        }
        return $this->text('emails.custom')
            ->from($this->from, $this->name)
            ->subject($this->subject)
            ->with(['content' => $this->content]);
    }
}
