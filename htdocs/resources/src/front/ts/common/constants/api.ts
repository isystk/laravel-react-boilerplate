import Env from '../env/'

/**
 * BFF（バックエンドフォーフロントエンド）用の URL を作成する
 * @param path
 */
const getBffUrl = (path: string): string =>
{
  const url = [Env.externalEndpointUrl, path].join('')
  return url
}

/** API のエンドポイント */
export const API_ENDPOINT = {
  /** ログイン状態チェック */
  LOGIN_CHECK: getBffUrl('/loginCheck'),
  /** ログイン */
  LOGIN: getBffUrl('/authenticate'),
  /** ログアウト */
  LOGOUT: getBffUrl('/logout'),
  /** 共通定数 */
  COMMON_CONST: getBffUrl('/common/const'),

  /** 商品一覧データ取得 */
  SHOPS: getBffUrl('/shops'),
  /** お気に入りデータ取得 */
  LIKES: getBffUrl('/likes'),
}
