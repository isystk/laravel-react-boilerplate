import React from "react";
import Router from "@/router";
import axios from "axios";
import { Provider } from "react-redux";
import { createRoot } from 'react-dom/client';
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
        const root = createRoot(container);
        root.render(
            <Provider store={store}>
                <Router session={session} />
            </Provider>
        );
    }
};

const init = async () => {
    const params = new URLSearchParams();
    const url = "/api/session";
    try {
        const response = await axios.post(url, params);
        render(response.data);
    } catch (e) {
        render({} as Session);
    }
};

// start
init();
