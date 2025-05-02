<?php

namespace App\Mail;

use App\Domain\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutCompleteMailable extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;
    private int $amount;
    /**
     * @var array{
     *     name: string,
     *     quantity: int,
     *     price: int
     * }
     */
    private array $orderItems;

    public function __construct(
        User $user,
        int $amount,
        array $orderItems
    ) {
        $this->user = $user;
        $this->amount = $amount;
        $this->orderItems = $orderItems;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this
            ->from(config('mail.from.address'))
            ->view('mails.checkout_complete_html')
            ->text('mails.checkout_complete_text')
            ->subject(config('const.mail.subject.checkout_complete_to_user'))
            ->with([
                'user' => $this->user,
                'amount' => $this->amount,
                'orderItems' => $this->orderItems,
            ]);
    }
}
