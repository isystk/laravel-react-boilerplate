import { IEnv } from '@/@types/IEnv';

let Env = {
  appName: 'LaraEC',
  envName: 'local',
  endpointUrl: 'https://localhost/api',
  stripeKey: import.meta.env.VITE_STRIPE_KEY,
} as IEnv;
if (import.meta.env.NODE_ENV === 'production') {
  Env = {
    ...Env,
    envName: import.meta.env.VITE_ENV_NAME,
    endpointUrl: import.meta.env.VITE_ENDPOINT_URL,
  } as IEnv;
}

console.log('Env:', Env);

export default Env;
