//  ----------------------------------------------------------------------------
//  System const values
//  ----------------------------------------------------------------------------

/** システム アプリ名 */
const APP_NAME = process.env.APP_NAME

/** システム アプリ説明 */
const APP_DESCRIPTION = process.env.APP_DESCRIPTION

/** システム デフォルトロケール */
const LOCALE = process.env.NEXT_PUBLIC_LOCALE as 'ja' | 'en'

/** システム モード */
const APP_MODE = process.env.NODE_ENV

/** システム APP URL */
const APP_URL = process.env.NEXT_PUBLIC_APP_URL

/** Dateオブジェクト 文字列表現デフォルトフォーマット */
const DATE_FORMAT = 'yyyy-MM-dd HH:mm:ss'

/** エンドポイント URL */
const ENDPOINT_URL = process.env.NEXT_PUBLIC_ENDPOINT_URL

/** Stripe 公開鍵 */
const STRIPE_KEY = process.env.NEXT_PUBLIC_STRIPE_KEY

/** Google Analytics トラッキングID */
const GA_TRAKING_ID = process.env.NEXT_PUBLIC_GA_TRAKING_ID

type LOCALSTORAGE = {
  User
}
export type LOCALSTORAGE_KEYS = keyof LOCALSTORAGE

export {
  APP_NAME,
  APP_DESCRIPTION,
  LOCALE,
  APP_MODE,
  APP_URL,
  DATE_FORMAT,
  ENDPOINT_URL,
  STRIPE_KEY,
  GA_TRAKING_ID,
}
