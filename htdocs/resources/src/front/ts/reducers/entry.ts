// Reducerは、Stateの状態をデザインして、アクションをハンドリングします。
// また、Reducerは、前の状態とアクションを取り、次の状態を返す純粋な関数です。

import { Reducer } from "redux";
import * as object_assign from "object-assign";
import * as _ from "lodash";

import { User } from "../store/StoreTypes";
import {
  EntryAppAction,
  CREATE_USER,
} from "../actions/index";

const initialState: User = {
};

export function EntryReducer(
  state = initialState,
  action: EntryAppAction
): User {

  switch (action.type) {
    case CREATE_USER:
      const data = action.values;
      return data;
    default:
      return state;
  }

  return state;
}

export default EntryReducer;
