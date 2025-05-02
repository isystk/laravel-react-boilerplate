import MainService from '@/services/main';
import { Api } from '@/constants/api';
import ShopState from "@/stores/state/shop";

export default class ShopService {
  main: MainService;
  shop: ShopState;

  constructor(main: MainService) {
    this.main = main;
    this.shop = main.root.shop;
  }

  // 商品データを取得する
  async readStocks(pageNo = 1) {
    // ローディングを表示する
    this.main.showLoading();
    const { stocks } = await fetch(`${Api.shops}?page=${pageNo}`).then(res => res.json());
    this.shop.stocks = {
      ...stocks,
    };
    // ローディングを非表示にする
    this.main.hideLoading();
    this.main.setRootState();
  }
}
