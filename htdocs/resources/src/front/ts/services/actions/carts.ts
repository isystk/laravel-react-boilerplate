import { Action } from "redux";
import { Dispatch } from "redux";
import { Url } from "@/constants/url";
import { API_ENDPOINT } from "@/constants/api";
import { API } from "@/utilities/api";
import { Carts } from "@/stores/StoreTypes";
import { push } from "connected-react-router";

/**
 * APIで返却されるデータ型を定義
 */
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

export const removeCart = (stockId: number) => async (
    dispatch: Dispatch
): Promise<void> => {
    try {
        const response = await API.post(API_ENDPOINT.REMOVE_MYCART, {
            stock_id: stockId
        });
        if (response.result) {
            dispatch({ type: READ_CARTS, response });
            dispatch(push(Url.MYCART));
        }
    } catch (e) {
        dispatch(push(Url.LOGIN));
    }
};

export const checkout = (stockId: number) => async (
    dispatch: Dispatch
): Promise<void> => {
    try {
        const response = await API.post(API_ENDPOINT.REMOVE_MYCART, {
            stock_id: stockId
        });
        if (response.result) {
            dispatch(push(Url.MYCART));
        }
    } catch (e) {
        dispatch(push(Url.LOGIN));
    }
};
