import React from "react";
import Router from "@/router";
import axios from "axios";
import { Provider } from "react-redux";
import * as ReactDOM from "react-dom/client";
import { createStore, applyMiddleware } from "redux";
import { composeWithDevTools } from "redux-devtools-extension";
import thunk from "redux-thunk";

import reducers from "@/stores";
import { Session } from "@/services/auth";
// 開発環境の場合は、redux-devtools-extension を利用できるようにする
const enhancer =
    process.env.NODE_ENV === "development"
        ? composeWithDevTools(applyMiddleware(thunk))
        : applyMiddleware(thunk);
const store = createStore(reducers, enhancer);

const render = (session: Session) => {
    console.log("session", session);
    const container = document.getElementById("react-root");
    if (container) {
        const root = ReactDOM.createRoot(container);
        root.render(
            <Provider store={store}>
                <Router session={session} />
            </Provider>
        );
    }
};

const init = () => {
    const params = new URLSearchParams();
    const url = "/session";
    axios.post(url, params).then((response) => {
        render(response.data);
    });
};

// start
init();
