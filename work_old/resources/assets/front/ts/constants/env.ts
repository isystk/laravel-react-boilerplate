import EnvLocal from "./env.local";
import { IEnv } from "@/@types/IEnv";

console.log("process.env.NODE_ENV: ", process.env.NODE_ENV);

let Env: IEnv;
if (process.env.NODE_ENV === "production") {
    Env = {
        envName: process.env.MIX_ENV_NAME,
        internalEndpointUrl: process.env.MIX_INTERNAL_ENDPOINT_URL,
        externalEndpointUrl: process.env.MIX_EXTERNAL_ENDPOINT_URL,
    } as IEnv;
} else {
    /** docker でビルドされていない場合は、 .env.local から値を取ってくる */
    Env = EnvLocal;
}

console.log("Env:", Env);

export default Env;
