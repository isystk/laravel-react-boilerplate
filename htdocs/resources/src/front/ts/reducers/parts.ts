// Reducerは、Stateの状態をデザインして、アクションをハンドリングします。
// また、Reducerは、前の状態とアクションを取り、次の状態を返す純粋な関数です。

import * as _ from "lodash";

import { Parts } from "../store/StoreTypes";
import {
  PartsAppAction,
  SHOW_MV,
  HIDE_MV,
  SHOW_OVERLAY,
  HIDE_OVERLAY,
  SHOW_LOADING,
  HIDE_LOADING,
  TOGGLE_MENU,
  CLOSE_MENU,
} from "../actions/index";

const initialState: Parts = {
  isShowMv: false,
  isShowOverlay: false,
  isShowLoading: false,
  isSideMenuOpen: false
};

export function PartsReducer(
  state = initialState,
  action: PartsAppAction
): Parts {

  switch (action.type) {
    case SHOW_MV:
      return {...state, isShowMv: true };
    case HIDE_MV:
      return {...state, isShowMv: false };
    case SHOW_OVERLAY:
      return {...state, isShowOverlay: true };
    case HIDE_OVERLAY:
      return {...state, isShowOverlay: false };
    case SHOW_LOADING:
      return {...state, isShowLoading: true };
    case HIDE_LOADING:
      return {...state, isShowLoading: false };
    case TOGGLE_MENU:
      return {...state, isSideMenuOpen: !state.isSideMenuOpen };
    case CLOSE_MENU:
      return {...state, isSideMenuOpen: false };
    default:
      return state;
  }

  return state;
}

export default PartsReducer;
