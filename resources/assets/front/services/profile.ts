import MainService from '@/services/main';
import { Api } from '@/constants/api';
import AuthState from '@/states/auth';

export default class ProfileService {
  main: MainService;
  auth: AuthState;

  constructor(main: MainService) {
    this.main = main;
    this.auth = main.root.auth;
  }

  /** プロフィール更新 */
  async updateProfile(data: { name: string; avatar?: string | null }) {
    this.main.showLoading();
    try {
      const response = await fetch(Api.PROFILE_UPDATE, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      });
      const result = await response.json();
      if (!result) {
        throw new Error('プロフィールの変更に失敗しました');
      }
      this.auth.name = result.name;
      this.auth.avatar_url = result.avatarUrl || null;
      this.main.showToastMessage('プロフィールを更新しました');
    } catch (e) {
      this.main.showToastMessage('プロフィールの変更に失敗しました');
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }
}
