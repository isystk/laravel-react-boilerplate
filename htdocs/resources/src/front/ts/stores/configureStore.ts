import { createBrowserHistory, History } from "history";
import createRootReducer from "@/services/reducers";
import { persistReducer } from "redux-persist";
import { routerMiddleware } from "connected-react-router";
import storage from "redux-persist/lib/storage";
import thunk from "redux-thunk";
import { configureStore } from "@reduxjs/toolkit";
import LocationState = History.LocationState;

const persistConfig = {
    key: "root",
    storage,
    blacklist: ["router"]
};

export const history: History<LocationState> = createBrowserHistory();

const persistedReducer = persistReducer(
    persistConfig,
    createRootReducer(history)
);

const myConfigureStore = () => {
    // 開発環境の場合は、redux-devtools-extension を利用できるようにする
    // const enhancer =
    //   process.env.NODE_ENV === 'development'
    //     ? composeWithDevTools(applyMiddleware(thunk, routerMiddleware(history)))
    //     : applyMiddleware(thunk, routerMiddleware(history))
    // const store = createStore(persistedReducer, preloadedState, enhancer)
    const store = configureStore({
        reducer: persistedReducer,
        devTools: process.env.NODE_ENV !== "production",
        middleware: [thunk, routerMiddleware(history)]
    });
    return store;
};

export default myConfigureStore;
