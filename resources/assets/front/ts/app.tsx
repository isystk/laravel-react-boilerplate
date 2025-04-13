import React from "react";
import Router from "@/router";
import axios from "axios";
import { createRoot } from 'react-dom/client';
import { Session } from "@/services/auth";
import { AppProvider } from "./stores/appContext";

const render = (session: Session) => {
    console.log("session", session);
    const container = document.getElementById("react-root");
    if (container) {
        const root = createRoot(container);
        root.render(
            <AppProvider>
                <Router session={session} />
            </AppProvider>
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
