import MainService from '@/services/main';
import { Api } from '@/constants/api';

export default class ContactService {
  main: MainService;

  constructor(main: MainService) {
    this.main = main;
  }

  async registContact(values): Promise<void> {
    this.main.showLoading();
    try {
      // 入力したお問い合わせ内容を送信する。
      const response = await fetch(Api.CONTACT_STORE, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(values),
      });
      await response.json();
    } catch (e) {
      this.main.showToastMessage('お問い合わせの登録に失敗しました');
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }
}
