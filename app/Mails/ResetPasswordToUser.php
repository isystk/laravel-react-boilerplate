<?php

namespace App\Mails;

use App\Domain\Entities\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordToUser extends Notification
{
    private User $user;

    private string $token;

    public function __construct(
        User $user,
        string $token
    ) {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->from(config('mail.from.address'))
            ->subject(config('const.mail.subject.reset_password_to_user'))
            ->view('mails.reset_password_to_user_html', [
                'user' => $this->user,
                'resetUrl' => $resetUrl,
            ])
            ->text('mails.reset_password_to_user_text', [
                'user' => $this->user,
                'resetUrl' => $resetUrl,
            ]);
    }
}
