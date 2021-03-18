// Reducerは、Stateの状態をデザインして、アクションをハンドリングします。
// また、Reducerは、前の状態とアクションを取り、次の状態を返す純粋な関数です。

import { Reducer } from "redux";
import * as object_assign from "object-assign";
import * as _ from "lodash";

import { Stocks, Page } from "../store/StoreTypes";
import
{
    ShopsAppAction,
    READ_STOCKS,
} from "../actions/index";

const initialState: Stocks = {
    data: [],
    page: {} as Page
};

export function StocksReducer(
    state = initialState,
    action: ShopsAppAction
): Stocks
{

    switch (action.type) {
        case READ_STOCKS:
            return action.response.stocks;
        default:
            return state;
    }

    return state;
}

export default StocksReducer;
