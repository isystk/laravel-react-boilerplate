import { Action } from "redux";
import axios from "axios";
import { Dispatch } from "redux";

//  アクションは『何か』が起こった時、Storeに『どんなデータ』を利用するかということを定義します。
//  ActionCreatorであるstore.dispatch()を使ってStoreに送信しますが、storeについてはこの記事の最後にまとめています。(※ こちら)
//  ただし、アプリケーションの状態がどのように変化するかはここでは指定しません。(→Reducerがやること)
//  あくまでどんな挙動があるかだけを定義します。
export interface PartsAppAction extends Action {
}

export const SHOW_MV = "SHOW_MV";
export const HIDE_MV = "HIDE_MV";
export const SHOW_OVERLAY = "SHOW_OVERLAY";
export const HIDE_OVERLAY = "HIDE_OVERLAY";
export const SHOW_LOADING = "SHOW_LOADING";
export const HIDE_LOADING = "HIDE_LOADING";
export const TOGGLE_MENU = "TOGGLE_MENU";
export const CLOSE_MENU = "CLOSE_MENU";

export const showMv = () => (dispatch: Dispatch): void => {
  dispatch({ type: SHOW_MV });
};

export const hideMv = () => (dispatch: Dispatch): void => {
  dispatch({ type: HIDE_MV });
};

export const showOverlay = () => (dispatch: Dispatch): void => {
  dispatch({ type: SHOW_OVERLAY });
};

export const hideOverlay = () => (dispatch: Dispatch): void => {
  dispatch({ type: HIDE_OVERLAY });
};

export const showLoading = () => (dispatch: Dispatch): void => {
  dispatch({ type: SHOW_LOADING });
};

export const hideLoading = () => (dispatch: Dispatch): void => {
  dispatch({ type: HIDE_LOADING });
};

export const toggleMenu = () => (dispatch: Dispatch): void => {
  dispatch({ type: TOGGLE_MENU });
};

export const closeMenu = () => (dispatch: Dispatch): void => {
  dispatch({ type: CLOSE_MENU });
};
