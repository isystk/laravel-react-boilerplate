import MainService from "@/services/main";
import { API } from "@/utilities/api";
import { API_ENDPOINT } from "@/constants/api";
import { Stock } from "@/stores/StoreTypes";

type Stocks = {
    current_page: number;
    total: number;
    data: Stock[];
};

const initialState = {
    current_page: 1,
    total: 0,
    data: [] as Stock[]
};

export default class ShopService {
    main: MainService;
    stocks: Stocks;

    constructor(main: MainService) {
        this.main = main;
        this.stocks = initialState;
    }

    // 商品データを取得する
    async readStocks(search = "") {
        // ローディングを表示する
        this.main.showLoading();
        const response = await API.get(`${API_ENDPOINT.SHOPS}${search}`);
        this.stocks = {
            current_page: response.stocks.current_page,
            total: response.stocks.total,
            data: response.stocks.data
        };
        // ローディングを非表示にする
        this.main.hideLoading();
        this.main.setAppRoot();
    }
}
