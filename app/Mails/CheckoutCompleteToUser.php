<?php

namespace App\Mails;

use App\Domain\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutCompleteToUser extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;

    private int $amount;

    /**
     * @var array<array{
     *     name: string,
     *     quantity: int,
     *     price: int
     * }>
     */
    private array $orderItems;

    /**
     * @param array<array{
     *     name: string,
     *     quantity: int,
     *     price: int
     * }> $orderItems
     */
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
            ->subject(config('const.mail.subject.checkout_complete_to_user'))
            ->view('mails.checkout_complete_to_user_html')
            ->text('mails.checkout_complete_to_user_text')
            ->with([
                'user' => $this->user,
                'amount' => $this->amount,
                'orderItems' => $this->orderItems,
            ]);
    }
}
