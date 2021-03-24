import { Action } from "redux";
import { Dispatch } from "redux";
import { URL } from "../common/constants/url";
import { API_ENDPOINT } from "../common/constants/api";
import { API } from "../utilities";
import { Carts } from "../store/StoreTypes";
import { push } from "connected-react-router";

export interface CartsAppAction extends Action {
  response: {
    result: boolean;
    carts: Carts;
  };
}

export const READ_CARTS = "READ_CARTS";

export const readCarts = () => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.post(API_ENDPOINT.MYCARTS);
  dispatch({ type: READ_CARTS, response });
};

export const addCart = (stockId: number) => async (
  dispatch: Dispatch
): Promise<void> => {
  const response = await API.post(API_ENDPOINT.ADD_MYCART, {
    stock_id: stockId,
  });
  if (response.result) {
    dispatch(push(URL.MYCART));
  }
};

export const removeCart = (stockId: number) => async (
  dispatch: Dispatch
): Promise<void> => {
  const response = await API.post(API_ENDPOINT.REMOVE_MYCART, {
    stock_id: stockId,
  });
  if (response.result) {
    dispatch(push(URL.MYCART));
  }
};
