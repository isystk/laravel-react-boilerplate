<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected string $id;
    protected string $title;
    protected mixed $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $id, string $title, mixed $data)
    {
        $this->id = $id;
        $this->title = $title;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('no-reply@laraec.com') // 送信元
            ->view('mails.' . $this->id)
            ->text('mails.' . $this->id . '_plain')
            ->subject($this->title)
            ->with([
                'data' => $this->data,
            ]);
    }
}
