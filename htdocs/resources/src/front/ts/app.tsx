import React from "react";
import ReactDom from "react-dom";
import Router from "@/router";
import axios from "axios";
import myConfigureStore, { history } from "@/stores/configureStore";
import { PersistGate } from "redux-persist/integration/react";
import { Provider } from "react-redux";
import { persistStore } from "redux-persist";

const store = myConfigureStore();
const pstore = persistStore(store);

export type Session = {
    created_at: string;
    email: string;
    email_verified_at: string | null;
    id: number;
    name: string;
    provider_id: string | null;
    provider_name: string | null;
    updated_at: string | null;
};

const render = (session: Session) => {
    console.log("session", session);
    ReactDom.render(
        <Provider store={store}>
            <PersistGate loading={<p>loading...</p>} persistor={pstore}>
                <Router history={history} session={session} />
            </PersistGate>
        </Provider>,
        document.getElementById("react-root")
    );
};

const init = () => {
    const params = new URLSearchParams();
    const url = "/session";
    axios.post(url, params).then(response => {
        render(response.data);
    });
};

// start
init();
