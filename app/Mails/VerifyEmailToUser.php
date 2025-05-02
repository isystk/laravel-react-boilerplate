<?php

namespace App\Mails;

use App\Domain\Entities\User;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailToUser extends BaseVerifyEmail
{
    private User $user;

    public function __construct(
        User $user,
    ) {
        $this->user = $user;
    }

    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->from(config('mail.from.address'))
            ->subject(config('const.mail.subject.verify_email_to_user'))
            ->view('mails.verify_email_to_user_html', [
                'user' => $this->user,
                'verifyUrl' => $verificationUrl,
            ])
            ->text('mails.verify_email_to_user_text', [
                'user' => $this->user,
                'verifyUrl' => $verificationUrl,
            ]);
    }

    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
    }
}
