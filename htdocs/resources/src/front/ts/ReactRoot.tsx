import React, { FC, useEffect } from "react";
import { ConnectedRouter } from "connected-react-router";
import routes from "@/routes";
import { History } from "history";
import LocationState = History.LocationState;
import { useDispatch } from "react-redux";
import { setSession, setCSRF, readConsts } from "./actions";

type Props = {
    responseSession: string;
    history: History<LocationState>;
};

const ReactRoot: FC<Props> = props => {
    const dispatch = useDispatch();

    useEffect(() => {
        // セッションのセット
        dispatch(setSession(props.responseSession));
        (async () => {
            // 定数のセット
            await dispatch(readConsts());
        })();
        // // if (window.laravelErrors['name'] === undefined
        //   && window.laravelErrors['email'] === undefined
        //   && window.laravelErrors['password'] === undefined) {
        //   dispatch(setName(''))
        //   dispatch(setEmail(''))
        //   dispatch(setRemember(false))
        // }
        // CSRFのセット
        const token = document.head.querySelector<HTMLMetaElement>(
            'meta[name="csrf-token"]'
        );
        if (token) {
            dispatch(setCSRF(token.content));
        }
    }, []);

    return (
        <ConnectedRouter history={props.history}>
            {routes(props.responseSession)}
        </ConnectedRouter>
    );
};

export default ReactRoot;
