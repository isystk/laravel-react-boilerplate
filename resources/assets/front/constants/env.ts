type EnvType = {
  /** アプリ名 */
  APP_NAME: string;
  /** 環境名 */
  ENV_NAME: string;
  /** エンドポイント URL */
  ENDPOINT_URL: string;
  /** Stripe キー */
  STRIPE_KEY: string;
};

let Env = {
  APP_NAME: 'LaraEC',
  ENV_NAME: 'local',
  ENDPOINT_URL: import.meta.env.VITE_ENDPOINT_URL,
  STRIPE_KEY: import.meta.env.VITE_STRIPE_KEY,
} as EnvType;
if (import.meta.env.VITE_ENV_NAME === 'production') {
  Env = {
    ...Env,
    ENV_NAME: import.meta.env.VITE_ENV_NAME,
  } as EnvType;
}

export default Env;
