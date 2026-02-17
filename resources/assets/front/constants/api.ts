import Env from '@/constants/env';

const getBffUrl = (path: string): string => {
  return [Env.ENDPOINT_URL, path].join('');
};

/** API のエンドポイント */
export const Api = {
  /** ログイン状態チェック */
  LOGIN_CHECK: getBffUrl('/session'),

  /** 共通定数 */
  CONST: getBffUrl('/const'),

  /** お気に入りデータ取得 */
  LIKE: getBffUrl('/like'),
  /** お気に入り追加 */
  LIKE_STORE: getBffUrl('/like/store'),
  /** お気に入り削除 */
  LIKE_DESTROY: getBffUrl('/like/destroy'),

  /** お問い合わせ登録 */
  CONTACT_STORE: getBffUrl('/contact/store'),

  /** 商品一覧データ取得 */
  STOCK: getBffUrl('/stock'),
  /** マイカートデータ取得 */
  MYCART: getBffUrl('/mycart'),
  /** カートに商品を追加する */
  MYCART_ADD: getBffUrl('/mycart/add'),
  /** カートから商品を削除する */
  MYCART_DELETE: getBffUrl('/mycart/delete'),
  /** Stripe用のペイメント作成 */
  MYCART_PAYMENT: getBffUrl('/mycart/payment'),
  /** 決済処理後の後処理 */
  MYCART_CHECKOUT: getBffUrl('/mycart/checkout'),

  /** プロフィール更新 */
  PROFILE_UPDATE: getBffUrl('/profile/update'),
};
