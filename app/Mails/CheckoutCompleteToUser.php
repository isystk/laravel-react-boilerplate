<?php

namespace App\Mails;

use App\Domain\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutCompleteToUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array<array{
     *     name: string,
     *     quantity: int,
     *     price: int
     * }> $orderItems
     */
    public function __construct(private User $user, private int $amount, private array $orderItems) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this
            ->from(config('mail.from.address'))
            ->subject(config('const.mail.subject.checkout_complete_to_user'))
            ->view('mails.checkout_complete_to_user_html')
            ->text('mails.checkout_complete_to_user_text')
            ->with([
                'user'       => $this->user,
                'amount'     => $this->amount,
                'orderItems' => $this->orderItems,
            ]);
    }
}
