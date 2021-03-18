// Reducerは、Stateの状態をデザインして、アクションをハンドリングします。
// また、Reducerは、前の状態とアクションを取り、次の状態を返す純粋な関数です。

import { Reducer } from "redux";
import * as object_assign from "object-assign";
import * as _ from "lodash";

import { Remind } from "../store/StoreTypes";
import {
  RemindAppAction,
  REMIND_CHECK_OK,
} from "../actions/index";

const initialState: Remind = {
  isValid: false
};

export function RemindReducer(
  state = initialState,
  action: RemindAppAction
): Remind {

  switch (action.type) {
    case REMIND_CHECK_OK:
      return {isValid: true};
    default:
      return state;
  }

  return state;
}

export default RemindReducer;
