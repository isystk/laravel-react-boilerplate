import { combineReducers, createStore, ReducersMapObject } from "redux";
import { AuthReducer } from "../reducers/auth";
import { ConstsReducer } from "../reducers/consts";
import { StocksReducer } from "../reducers/stocks";

const reducers: ReducersMapObject = {
};

declare let window: any;

const rootReducer = combineReducers({
  AuthReducer,
  ConstsReducer,
  StocksReducer,
});


export default createStore(
  rootReducer,
  window.devToolsExtension ? window.devToolsExtension() : undefined
);
