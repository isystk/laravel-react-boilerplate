import MainService from '@/services/main';
import { Api } from '@/constants/api';
import CartState from '@/states/cart';

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
      const response = await fetch(Api.MYCARTS, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const { result, carts } = await response.json();
      if (result) {
        Object.assign(this.cart, carts);
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
      const response = await fetch(Api.ADD_MYCART, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          stock_id: stockId,
        }),
      });
      const { result, carts } = await response.json();
      if (result) {
        Object.assign(this.cart, carts);
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
      const response = await fetch(Api.REMOVE_MYCART, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          cart_id: cartId,
        }),
      });
      const { result, carts } = await response.json();
      if (result) {
        Object.assign(this.cart, carts);
      }
    } catch (e) {
      this.main.showToastMessage('マイカートの削除に失敗しました');
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }

  async payment(stripe, elements, amount, username): Promise<void> {
    this.main.showLoading();
    try {
      //paymentIntentの作成を（ローカルサーバ経由で）リクエスト
      const response = await fetch(Api.CREATE_PAYMENT, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          amount,
          username,
        }),
      });
      const { client_secret } = await response.json();

      //client_secretを利用して（確認情報をStripeに投げて）決済を完了させる
      const confirmRes = await stripe.confirmCardPayment(client_secret, {
        payment_method: {
          // @ts-ignore
          card: elements.getElement('cardNumber'),
          billing_details: {
            name: username,
          },
        },
      });

      if (!confirmRes.paymentIntent || confirmRes.paymentIntent.status !== 'succeeded') {
        throw new Error();
      }
      // 決算処理が完了したら、注文履歴に追加してマイカートから商品を削除する。
      await fetch(Api.CHECKOUT, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });
    } catch (e) {
      this.main.showToastMessage('決算処理に失敗しました');
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }
}
