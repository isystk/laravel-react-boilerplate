import { Action } from "redux";
import axios from "axios";
import { Dispatch } from "redux";
import { API_ENDPOINT } from "../common/constants/api";
import { API } from "../utilities";
import { Stocks } from "../store/StoreTypes";

//  アクションは『何か』が起こった時、Storeに『どんなデータ』を利用するかということを定義します。
//  ActionCreatorであるstore.dispatch()を使ってStoreに送信しますが、storeについてはこの記事の最後にまとめています。(※ こちら)
//  ただし、アプリケーションの状態がどのように変化するかはここでは指定しません。(→Reducerがやること)
//  あくまでどんな挙動があるかだけを定義します。
export interface ShopsAppAction extends Action
{
    response: {
        result: boolean;
        stocks: Stocks;
    }
}

export const READ_STOCKS = "READ_STOCKS";

export const readShops = () => async (dispatch: Dispatch): Promise<void> =>
{
    const response = await API.get(API_ENDPOINT.SHOPS);
    dispatch({ type: READ_STOCKS, response });
};
