import MainService from '@/services/main';
import { Api } from '@/constants/api';
import LikeState from '@/states/like';
import i18n from '@/i18n';

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
      const response = await fetch(Api.LIKE);
      const { stockIds } = await response.json();
      this.like.stockIds = stockIds;
    } catch (e) {
      this.main.showToastMessage(i18n.t('error:service.likeFetchFailed'));
    }

    this.main.hideLoading();
  }

  async addLikeAsync(id: number) {
    this.main.showLoading();
    try {
      const response = await fetch(Api.LIKE_STORE, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          id,
        }),
      });
      const { result } = await response.json();
      if (result) {
        this.main.showToastMessage(i18n.t('error:service.likeAdded'));
        this.like.stockIds = [id, ...this.like.stockIds];
      }
    } catch (e) {
      this.main.showToastMessage(i18n.t('error:service.likeAddFailed'));
    }
    this.main.hideLoading();
  }

  async removeLikeAsync(id: number) {
    this.main.showLoading();
    try {
      const response = await fetch(Api.LIKE_DESTROY + '/' + id, {
        method: 'POST',
      });
      const { result } = await response.json();
      if (result) {
        this.like.stockIds = this.like.stockIds.filter(n => n !== id);
      }
    } catch (e) {
      this.main.showToastMessage(i18n.t('error:service.likeRemoveFailed'));
      throw e;
    } finally {
      this.main.hideLoading();
    }
  }
}
