import { ENDPOINT_URL } from './index'

/**
 * BFF（バックエンドフォーフロントエンド）用の URL を作成する
 */

/** API のエンドポイント */
export const Api = {
  /* Public */
  Product: [ENDPOINT_URL, '/product'].join(''),
  Payment: [ENDPOINT_URL, '/payment'].join(''),
  CancelRequest: [ENDPOINT_URL, '/cancel-request'].join(''),
  CancelConfirm: [ENDPOINT_URL, '/cancel-confirm'].join(''),
  Cancel: [ENDPOINT_URL, '/cancel'].join(''),

  /* Auth */
  Login: [ENDPOINT_URL, '/login'].join(''),
  LoginCheck: [ENDPOINT_URL, '/login-check'].join(''),
  Logout: [ENDPOINT_URL, '/logout'].join(''),

  /* Private */
  AdminSubscriber: [ENDPOINT_URL, '/admin/subscriber'].join(''),
  AdminSubscriberTrend: [ENDPOINT_URL, '/admin/subscriberTrend'].join(''),
  AdminCancel: [ENDPOINT_URL, '/admin/cancel'].join(''),
}
