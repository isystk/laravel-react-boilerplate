// Reducerは、Stateの状態をデザインして、アクションをハンドリングします。
// また、Reducerは、前の状態とアクションを取り、次の状態を返す純粋な関数です。

import * as _ from "lodash";

import { Auth } from "../store/StoreTypes";
import {
  AuthAppAction,
  AUTH_CHECK,
  AUTH_LOGIN,
  AUTH_LOGOUT,
} from "../actions/index";

const initialState: Auth = {
  isLogin: false
};

export function AuthReducer(
  state = initialState,
  action: AuthAppAction
): Auth {

  switch (action.type) {
    case AUTH_CHECK:
    case AUTH_LOGIN:
      const { response } = action;
      if (!response) {
        return {
          isLogin: false,
        };
      }
      return {
        isLogin: true,
        familyName: response[0].familyName,
      };
    case AUTH_LOGOUT:
      return { isLogin: false };
    default:
      return state;
  }

  return state;
}

export default AuthReducer;
