import MainService from '@/services/main';
import { Api } from '@/constants/api';

export type ContactForm = {
  user_name: string;
  email: string;
  gender: string;
  age: string;
  title: string;
  contact: string;
  url: string;
  imageBase64_1: string;
  imageBase64_2: string;
  imageBase64_3: string;
  caution: string[];
};

export default class ContactService {
  main: MainService;

  constructor(main: MainService) {
    this.main = main;
  }

  async registContact(values: ContactForm): Promise<void> {
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
