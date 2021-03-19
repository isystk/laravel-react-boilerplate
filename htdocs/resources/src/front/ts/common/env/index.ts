import EnvLocal from "./env.local";
import { IEnv } from "../../interfaces/app/IEnv";

console.log("process.env.NODE_ENV: ", process.env.NODE_ENV);
console.log("process.env.BUILD_ENV: ", process.env.BUILD_ENV);

let Env: IEnv;
if (process.env.NODE_ENV === 'production') {
  /** docker のビルド環境の環境変数から値を取ってくる */
  Env = {
    envName: process.env.MIX_ENV_NAME,
    internalEndpointUrl: process.env.MIX_INTERNAL_ENDPOINT_URL,
    externalEndpointUrl: process.env.MIX_EXTERNAL_ENDPOINT_URL
  } as IEnv
} else {
  /** docker でビルドされていない場合は、 .env.local から値を取ってくる */
  Env = EnvLocal;
}

console.log("Env:", Env);

export default Env;
