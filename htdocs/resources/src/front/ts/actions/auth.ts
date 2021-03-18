import { Action } from "redux";
import axios from "axios";
import { Dispatch } from "redux";
import { API_ENDPOINT } from "../common/constants/api";
import { API } from "../utilities";
import { Auth } from "../store/StoreTypes";

//  アクションは『何か』が起こった時、Storeに『どんなデータ』を利用するかということを定義します。
//  ActionCreatorであるstore.dispatch()を使ってStoreに送信しますが、storeについてはこの記事の最後にまとめています。(※ こちら)
//  ただし、アプリケーションの状態がどのように変化するかはここでは指定しません。(→Reducerがやること)
//  あくまでどんな挙動があるかだけを定義します。
export interface AuthAppAction extends Action {
  response: Auth[];
}

export const AUTH_CHECK = "AUTH_CHECK";
export const AUTH_LOGIN = "AUTH_LOGIN";
export const AUTH_LOGOUT = "AUTH_LOGOUT";

export const authCheck = () => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.post(API_ENDPOINT.LOGIN_CHECK);
  dispatch({ type: AUTH_CHECK, response });
};

export const authLogin = (values: any) => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.post(API_ENDPOINT.LOGIN, values );
  dispatch({ type: AUTH_LOGIN, response });
};

export const authLogout = () => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.post(API_ENDPOINT.LOGOUT);
  dispatch({ type: AUTH_LOGOUT, response });
};
