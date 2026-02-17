import MainService from '@/services/main';
import { Api } from '@/constants/api';
import i18n from '@/i18n';

export default class StockService {
  main: MainService;

  constructor(main: MainService) {
    this.main = main;
  }

  // 商品データを取得する
  async readStocks(pageNo = 1) {
    this.main.showLoading();
    try {
      return await fetch(`${Api.STOCK}?page=${pageNo}`).then(res => res.json());
    } catch (e) {
      this.main.showToastMessage(i18n.t('error:service.stockFetchFailed'));
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }
}
