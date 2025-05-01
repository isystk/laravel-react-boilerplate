/**
 * Env インターフェイス
 */
export interface IEnv
{
  /** アプリ名 */
  appName: string
  /** 環境名 */
  envName: string
  /** エンドポイント URL */
  endpointUrl: string
  /** Stripe キー */
  stripeKey: string
}
