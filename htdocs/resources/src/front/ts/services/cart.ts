import MainService from "@/services/main";
import { API } from "@/utilities/api";
import { API_ENDPOINT } from "@/constants/api";
import { Carts } from "@/stores/StoreTypes";

const initialState: Carts = {
    data: [],
    message: "",
    username: "",
    count: 0,
    sum: 0
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
            const response = await API.post(API_ENDPOINT.MYCARTS);
            if (response.result) {
                this.carts = response.carts;
            }
        } catch (e) {
            alert("マイカートの取得に失敗しました");
        }
        // ローディングを非表示にする
        this.main.hideLoading();
        this.main.setAppRoot();
    }

    async addStock(stockId: number): Promise<boolean> {
        let result = false;
        // ローディングを表示する
        this.main.showLoading();
        try {
            const response = await API.post(API_ENDPOINT.ADD_MYCART, {
                stock_id: stockId
            });
            if (response.result) {
                this.carts = response.carts;
                result = true;
            }
        } catch (e) {
            alert("マイカートの追加に失敗しました");
        }
        // ローディングを非表示にする
        this.main.hideLoading();
        this.main.setAppRoot();
        return result;
    }

    async removeCart(stockId: number): Promise<boolean> {
        let result = false;
        // ローディングを表示する
        this.main.showLoading();
        try {
            const response = await API.post(API_ENDPOINT.REMOVE_MYCART, {
                stock_id: stockId
            });
            if (response.result) {
                this.carts = response.carts;
                result = true;
            }
        } catch (e) {
            alert("マイカートの削除に失敗しました");
        }
        // ローディングを非表示にする
        this.main.hideLoading();
        this.main.setAppRoot();
        return result;
    }
}
