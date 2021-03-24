import * as _ from "lodash";

import { Consts } from "../store/StoreTypes";
import { ConstsAppAction, READ_CONSTS } from "../actions/index";

const initialState: Consts = {};

export function ConstsReducer(
  state = initialState,
  action: ConstsAppAction
): Consts
{
  switch (action.type) {
    case READ_CONSTS:
      // APIで返却されるJSONとStoreに保存するオブジェクトのフォーマットが異なるので加工する
      console.log("READ_CONSTS", _.mapKeys(action.response.consts.data, "name"))
      return _.mapKeys(action.response.consts.data, "name");
    default:
      return state;
  }

  return state;
}

export default ConstsReducer;
