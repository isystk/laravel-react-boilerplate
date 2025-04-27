import EnvLocal from "./env.local";
import { IEnv } from "@/@types/IEnv";

console.log("import.meta.env.NODE_ENV: ", import.meta.env.NODE_ENV);

let Env: IEnv;
if (import.meta.env.NODE_ENV === "production") {
    Env = {
        envName: import.meta.env.MIX_ENV_NAME,
        internalEndpointUrl: import.meta.env.MIX_INTERNAL_ENDPOINT_URL,
        externalEndpointUrl: import.meta.env.MIX_EXTERNAL_ENDPOINT_URL,
    } as IEnv;
} else {
    /** docker でビルドされていない場合は、 .env.local から値を取ってくる */
    Env = EnvLocal;
}

console.log("Env:", Env);

export default Env;
