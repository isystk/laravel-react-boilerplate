import MainService from "@/services/main";
import { Api } from "@/constants/api";

export interface Page {
    total: number;
    current_page: number;
    last_page: number;
    first_page_url: string;
    prev_page_url: string;
    next_page_url: string;
    last_page_url: string;
}

export interface Stock {
    id: number;
    name: string;
    detail: string;
    price: number;
    imgpath: string;
    quantity: number;
    created_at: Date;
    updated_at: Date;
    isLike: boolean;
}

type Stocks = {
    current_page: number;
    total: number;
    data: Stock[];
};

const initialState = {
    current_page: 1,
    total: 0,
    data: [] as Stock[],
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
        const response = await fetch(`${Api.shops}${search}`);
        const { stocks } = await response.json();
        this.stocks = {
            current_page: stocks.current_page,
            total: stocks.total,
            data: stocks.data,
        };
        // ローディングを非表示にする
        this.main.hideLoading();
        this.main.setAppRoot();
    }
}
