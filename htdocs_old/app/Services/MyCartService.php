<?php

namespace App\Services;

use App\Models\Cart;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\StockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\MailNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class MyCartService extends BaseService
{
    /**
     * @var StockRepository
     */
    protected StockRepository $stockRepository;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    /**
     * @var CartRepository
     */
    protected CartRepository $cartRepository;

    public function __construct(
        Request         $request,
        StockRepository $stockRepository,
        OrderRepository $orderRepository,
        CartRepository $cartRepository,
    )
    {
        parent::__construct($request);
        $this->stockRepository = $stockRepository;
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
    }

    /**
     * @param Cart $cart
     * @return array<string>
     */
    public function searchMyCart(Cart $cart): array
    {
        return $this->convertToMycart($cart);
    }

    /**
     * @param Cart $cart
     * @return array<string, string>
     */
    private function convertToMycart(Cart $cart): array
    {
        $carts = $cart->showCart();
        $datas = $carts['data']->map(function ($cart, $key) {
            $data = [];
            $data['id'] = $cart->stock->id;
            $data['name'] = $cart->stock->name;
            $data['price'] = $cart->stock->price;
            $data['imgpath'] = $cart->stock->imgpath;
            return $data;
        });

        return [
            'data' => $datas,
            'username' => Auth::user()->email,
            'count' => $carts['count'],
            'sum' => $carts['sum']
        ];
    }

    /**
     * @param Cart $cart
     * @param string $stock_id
     * @return string
     */
    public function addMyCart(Cart $cart, string $stock_id)
    {
        //カートに追加の処理
        return $cart->addCart($stock_id);
    }

    /**
     * @param Cart $cart
     * @param string $stock_id
     * @return string
     */
    public function deleteMyCart(Cart $cart, string $stock_id)
    {
        //カートから削除の処理
        return $cart->deleteCart($stock_id);
    }

    /**
     * @param Request $request
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function createPayment(Request $request): PaymentIntent
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        return $stripe->paymentIntents->create([
            'amount' => $request->amount,
            'currency' => 'jpy',
            'description' => 'LaraEC',
            'metadata' => [
                'username' => $request->username
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param Cart $cart
     * @throws \Exception
     */
    public function checkout(Request $request, Cart $cart): void
    {
        // Stripe::setApiKey(env('STRIPE_SECRET'));

        // // 料金を支払う人
        // $customer = Customer::create(array(
        //   'email' => $request->stripeEmail,
        //   'source' => $request->stripeToken
        // ));

        $data = $cart->showCart();

        DB::beginTransaction();
        try {    //

            // // 料金の支払いを実行
            // $charge = Charge::create(array(
            //   'customer' => $customer->id,
            //   'amount' => $data['sum'],
            //   'currency' => 'jpy'
            // ));

            $stocks = [];

            // 発注履歴に追加する。
            foreach ($data['data'] as $my_cart) {
                $stock = $this->stockRepository->find($my_cart->stock_id);

                $order = $this->orderRepository->create([
                    'stock_id' => $my_cart->stock_id,
                    'user_id' => $my_cart->user_id,
                    'price' => $stock->price,
                    'quantity' => 1,
                ]);

                // 在庫を減らす
                $quantity = $stock->quantity - $order->quantity;
                $this->stockRepository->update(
                    [
                        'quantity' => $quantity
                    ],
                    $stock->id
                );

                array_push($stocks, (object)[
                    'name' => $stock->name,
                    'quantity' => $order->quantity,
                    'price' => $order->price,
                ]);
            }

            $user = Auth::user();

            $mailData = (object)[
                'name' => $user->name,
                'amount' => $data['sum'],
                'stocks' => $stocks,
            ];

            // メール送信
            Mail::to($user->email)
                ->send(new MailNotification('stock_complete', '商品の購入が完了しました', $mailData));

            // カートからすべての商品を削除
            $cart->deleteMyCart();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \RuntimeException($e);
        }
    }
}
