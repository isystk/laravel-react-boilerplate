import MainService from "@/services/main";
import { Api } from "@/constants/api";

export type Carts = {
    data: Cart[];
    message: string;
    username: string;
    count: number;
    sum: number;
};

export type Cart = {
    id: number;
    name: string;
    detail: string;
    price: number;
    imgpath: string;
    quantity: number;
    created_at: Date;
    updated_at: Date;
};

type Form = {
    amount: number;
    username: string;
};

const initialState: Carts = {
    data: [],
    message: "",
    username: "",
    count: 0,
    sum: 0,
};

export default class CartService {
    main: MainService;
    carts: Carts;

    constructor(main: MainService) {
        this.main = main;
        this.carts = initialState;
    }

    async readCarts() {
        // ローディングを表示する
        this.main.showLoading();
        try {
            const response = await fetch(Api.myCarts, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
            const { result, carts } = await response.json();
            if (result) {
                this.carts = carts;
            }
        } catch (e) {
            alert("マイカートの取得に失敗しました");
        }
        // ローディングを非表示にする
        this.main.hideLoading();
        this.main.setAppRoot();
    }

    async addCart(stockId: number): Promise<void> {
        // ローディングを表示する
        this.main.showLoading();
        try {
            const response = await fetch(Api.addMyCarts, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify( {
                    stock_id: stockId,
                }),
            });
            const { result, carts } = await response.json();
            if (result) {
                this.carts = carts;
            }
        } catch (e) {
            alert("マイカートの追加に失敗しました");
        }
        // ローディングを非表示にする
        this.main.hideLoading();
        this.main.setAppRoot();
    }

    async removeCart(cartId: number): Promise<void> {
        // ローディングを表示する
        this.main.showLoading();
        try {
            const response = await fetch(Api.removeMyCart, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify( {
                    cart_id: cartId,
                })
            });
            const { result, carts } = await response.json();
            if (result) {
                this.carts = carts;
            }
        } catch (e) {
            alert("マイカートの削除に失敗しました");
        }
        // ローディングを非表示にする
        this.main.hideLoading();
        this.main.setAppRoot();
    }

    async payment(stripe, elements, values: Form): Promise<boolean> {
        // ローディングを表示する
        this.main.showLoading();
        try {
            //paymentIntentの作成を（ローカルサーバ経由で）リクエスト
            const response = await fetch(Api.createPayment, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify( {
                    amount: values.amount,
                    username: values.username,
                }),
            });
            const { client_secret } = await response.json();

            //client_secretを利用して（確認情報をStripeに投げて）決済を完了させる
            const confirmRes = await stripe.confirmCardPayment(client_secret, {
                payment_method: {
                    // @ts-ignore
                    card: elements.getElement("cardNumber"),
                    billing_details: {
                        name: values.username,
                    },
                },
            });

            if (
                !confirmRes.paymentIntent ||
                confirmRes.paymentIntent.status !== "succeeded"
            ) {
                throw new Error();
            }
            // 決算処理が完了したら、注文履歴に追加してマイカートから商品を削除する。
            await fetch(Api.checkout, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
        } catch (e) {
            alert("決算処理に失敗しました");
            return false;
        } finally {
            this.main.hideLoading();
            this.main.setAppRoot();
        }
        return true;
    }
}
