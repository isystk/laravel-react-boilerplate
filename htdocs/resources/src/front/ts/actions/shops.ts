import { Action } from "redux";
import { Dispatch } from "redux";
import { URL } from "../common/constants/url";
import { API_ENDPOINT } from "../common/constants/api";
import { API } from "../utilities";
import { Stocks } from "../store/StoreTypes";
import { push } from "connected-react-router";

export interface ShopsAppAction extends Action {
  response: {
    result: boolean;
    stocks: Stocks;
  };
}

export const READ_STOCKS = "READ_STOCKS";

export const readShops = (search = "?page=1") => async (
  dispatch: Dispatch
): Promise<void> => {
  const response = await API.get(`${API_ENDPOINT.SHOPS}${search}`);
  dispatch({ type: READ_STOCKS, response });
  // URLを変更する
  dispatch(push(`${URL.TOP}${search}`));
};
