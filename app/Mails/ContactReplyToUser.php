<?php

namespace App\Mails;

use App\Domain\Entities\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyToUser extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private readonly Contact $contact, private readonly string $replyBody) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this
            ->from(config('mail.from.address'))
            ->subject(config('const.mail.subject.contact_reply_to_user'))
            ->text('mails.contact_reply_to_user_text')
            ->with([
                'contact'   => $this->contact,
                'replyBody' => $this->replyBody,
            ]);
    }
}
