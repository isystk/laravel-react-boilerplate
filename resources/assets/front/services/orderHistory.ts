import MainService from '@/services/main';
import { Api } from '@/constants/api';
import i18n from '@/i18n';

export default class OrderHistoryService {
  main: MainService;

  constructor(main: MainService) {
    this.main = main;
  }

  /**
   * 購入履歴データを取得する
   */
  async readOrderHistory() {
    this.main.showLoading();
    try {
      const { orders } = await fetch(Api.ORDER_HISTORY).then(res => res.json());
      this.main.root.orderHistory.orders = orders;
      return orders;
    } catch (e) {
      this.main.showToastMessage(i18n.t('error:service.orderHistoryFetchFailed'));
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }
}
