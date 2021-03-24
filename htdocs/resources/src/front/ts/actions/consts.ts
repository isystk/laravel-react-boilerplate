import { Action } from "redux";
import { Dispatch } from "redux";
import { API_ENDPOINT } from "../common/constants/api";
import { API } from "../utilities";
import { Const } from "../store/StoreTypes";

export interface ConstsAppAction extends Action {
  response: Const;
}

export const READ_CONSTS = "READ_CONSTS";

export const readConst = () => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.get(API_ENDPOINT.COMMON_CONST);
  dispatch({ type: READ_CONSTS, response });
};
