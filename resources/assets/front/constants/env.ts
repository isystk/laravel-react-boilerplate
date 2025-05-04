type EnvType = {
  /** アプリ名 */
  appName: string;
  /** 環境名 */
  envName: string;
  /** エンドポイント URL */
  endpointUrl: string;
  /** Stripe キー */
  stripeKey: string;
};

let Env = {
  appName: 'LaraEC',
  envName: 'local',
  endpointUrl: 'https://localhost/api',
  stripeKey: import.meta.env.VITE_STRIPE_KEY,
} as EnvType;
if (import.meta.env.NODE_ENV === 'production') {
  Env = {
    ...Env,
    envName: import.meta.env.VITE_ENV_NAME,
    endpointUrl: import.meta.env.VITE_ENDPOINT_URL,
  } as EnvType;
}

export default Env;
