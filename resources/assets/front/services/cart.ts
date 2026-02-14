import MainService from '@/services/main';
import { Api } from '@/constants/api';
import CartState from '@/states/cart';
import * as stripeJs from '@stripe/stripe-js';

export type PaymentForm = {
  stripe: stripeJs.Stripe;
  elements: stripeJs.StripeElements;
  amount: number;
  email: string;
};

export default class CartService {
  main: MainService;
  cart: CartState;

  constructor(main: MainService) {
    this.main = main;
    this.cart = main.root.cart;
  }

  async readCarts() {
    this.main.showLoading();
    try {
      const response = await fetch(Api.MYCART, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const result = await response.json();
      if (result) {
        Object.assign(this.cart, result);
      }
    } catch (e) {
      this.main.showToastMessage('マイカートの取得に失敗しました');
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }

  async addCart(stockId: number): Promise<void> {
    this.main.showLoading();
    try {
      const response = await fetch(Api.MYCART_ADD, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          stock_id: stockId,
        }),
      });
      const result = await response.json();
      if (result) {
        Object.assign(this.cart, result);
      }
    } catch (e) {
      this.main.showToastMessage('マイカートの追加に失敗しました');
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }

  async removeCart(cartId: number): Promise<void> {
    this.main.showLoading();
    try {
      const response = await fetch(Api.MYCART_DELETE, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          cart_id: cartId,
        }),
      });
      const result = await response.json();
      if (result) {
        Object.assign(this.cart, result);
      }
    } catch (e) {
      this.main.showToastMessage('マイカートの削除に失敗しました');
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }

  async payment({ stripe, elements, amount, email }: PaymentForm): Promise<void> {
    this.main.showLoading();
    try {
      // PaymentIntentの作成
      const response = await fetch(Api.MYCART_PAYMENT, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          amount,
          email,
        }),
      });
      const { client_secret } = await response.json();

      // Stripe側で決済処理を行う
      const confirmRes = await stripe.confirmCardPayment(client_secret, {
        payment_method: {
          // @ts-ignore
          card: elements.getElement('cardNumber'),
          billing_details: {
            name: email,
          },
        },
      });

      if (!confirmRes.paymentIntent || confirmRes.paymentIntent.status !== 'succeeded') {
        throw new Error('決済処理に失敗しました');
      }

      // Stripe側で決済処理が完了したら、注文履歴に追加してマイカートから商品を削除する。
      const checkoutRes = await fetch(Api.MYCART_CHECKOUT, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      if (!checkoutRes.ok) {
        console.error(
          'Stripe側の決済処理は成功したが、注文履歴への追加やマイカートからの削除に失敗しました',
        );
      }
    } catch (e) {
      this.main.showToastMessage('決済処理に失敗しました');
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }
}
