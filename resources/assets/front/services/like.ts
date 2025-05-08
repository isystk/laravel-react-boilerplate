import MainService from '@/services/main';
import { Api } from '@/constants/api';
import LikeState from '@/states/like';

export default class LikeService {
  main: MainService;
  like: LikeState;

  constructor(main: MainService) {
    this.main = main;
    this.like = main.root.like;
  }

  async readLikesAsync() {
    this.main.showLoading();
    try {
      const response = await fetch(Api.LIKES);
      const { likes } = await response.json();
      this.like.stockIds = likes.data;
    } catch (e) {
      this.main.showToastMessage('お気に入りの取得に失敗しました');
    }

    this.main.hideLoading();
  }

  async addLikeAsync(id: number) {
    this.main.showLoading();
    try {
      const response = await fetch(Api.LIKES_STORE, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          id: id,
        }),
      });
      const { result } = await response.json();
      if (result) {
        this.main.showToastMessage('お気に入りに追加しました');
        const newData: string = id + '';
        this.like.stockIds = [newData, ...this.like.stockIds];
      }
    } catch (e) {
      this.main.showToastMessage('お気に入りの追加に失敗しました');
    }
    this.main.hideLoading();
  }

  async removeLikeAsync(id: number) {
    this.main.showLoading();
    try {
      const response = await fetch(Api.LIKES_DESTROY + '/' + id, {
        method: 'POST',
      });
      const { result } = await response.json();
      if (result) {
        this.like.stockIds = this.like.stockIds.filter(n => n !== id + '');
      }
    } catch (e) {
      this.main.showToastMessage('お気に入りの削除に失敗しました');
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }
}
