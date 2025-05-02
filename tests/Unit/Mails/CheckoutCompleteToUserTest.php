<?php

namespace Tests\Unit\Mails;

use App\Mails\CheckoutCompleteToUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutCompleteToUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification(): void
    {
        Mail::fake();

        $user = $this->createDefaultUser([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);
        $amount = 1500;
        $orderItems = [
            ['name' => '商品A', 'quantity' => 1, 'price' => 1000],
            ['name' => '商品B', 'quantity' => 1, 'price' => 500],
        ];

        Mail::to($user->email)->send(new CheckoutCompleteToUser($user, $amount, $orderItems));

        Mail::assertSent(CheckoutCompleteToUser::class, static function ($mail) use ($user, $amount, $orderItems) {
            $mail->build();

            return $mail->hasTo($user->email)
                && $mail->view === 'mails.checkout_complete_to_user_html'
                && $mail->textView === 'mails.checkout_complete_to_user_text'
                && $mail->subject === config('const.mail.subject.checkout_complete_to_user')
                && $mail->from[0]['address'] === config('mail.from.address')
                && $mail->viewData['user'] === $user
                && $mail->viewData['amount'] === $amount
                && $mail->viewData['orderItems'] === $orderItems;
        });
    }
}
