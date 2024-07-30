<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderNodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public string $content = '';
    public array $files = [];
    public function __construct($to, $subject, $content, $files = [])
    {
        $user = auth('user')->user();
        if(!$user->email || !$user->email_password) {
            throw new \Exception('请配置邮箱信息');
        }
        $pubConfig = config('mail.mailers.smtp', []);
        $config = array_merge($pubConfig, [
            'username' => $user->email,
            'password' => $user->email_password,
        ]);
        config([
            "mail.mailers.user:$user->id" => $config
        ]);
        $this->mailer("user:$user->id");
        $this->from($user->email);
        $this->to($to);
        $this->subject = $subject;
        $this->content = $content;
        $this->files = array_map([\App\Utils\Order\OrderFiles::getInstance(), 'tryToFilePathAbsolute'], $files);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        foreach ($this->files as $file){
            $this->attach($file);
        }

        return $this->text('emails.custom')
          ->subject($this->subject)
          ->with(['content' => $this->content]);

    }
}
