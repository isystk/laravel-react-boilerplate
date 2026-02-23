<?php

namespace App\Mails;

use App\Domain\Entities\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordToUser extends Notification
{
    public function __construct(private readonly User $user, private readonly string $token) {}

    /**
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = config('app.url') . '/reset-password/' . $this->token
            . '?email=' . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->from(config('mail.from.address'))
            ->subject(config('const.mail.subject.reset_password_to_user'))
            ->view('mails.reset_password_to_user_html', [
                'user'     => $this->user,
                'resetUrl' => $resetUrl,
            ])
            ->text('mails.reset_password_to_user_text', [
                'user'     => $this->user,
                'resetUrl' => $resetUrl,
            ]);
    }
}
