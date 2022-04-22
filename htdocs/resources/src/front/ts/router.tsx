import React, { FC, useEffect } from "react";
import AuthCheck from "@/components/Auths/AuthCheck";
import ContactComplete from "@/pages/contact/complete";
import ContactCreate from "@/pages/contact";
import EMailForm from "@/pages/password/reset";
import Home from "@/pages/home";
import LocationState = History.LocationState;
import LoginForm from "@/pages/login";
import MyCart from "@/pages/mycart";
import NotFound from "@/components/NotFound";
import RegisterForm from "@/pages/register";
import ResetForm from "@/pages/password/reset/[id]";
import ShopComplete from "@/pages/complete";
import ShopTop from "@/pages";
import Verify from "@/pages/email/verify";
import { ConnectedRouter } from "connected-react-router";
import { History } from "history";
import { Route, Switch } from "react-router";
import { Session } from "@/app";
import { Url } from "@/constants/url";
import { setSession, setCSRF, readConsts } from "@/services/actions";
import { useDispatch } from "react-redux";

type Props = {
    session: Session;
    history: History<LocationState>;
};

const Router: FC<Props> = ({ session, history }) => {
    const dispatch = useDispatch();

    useEffect(() => {
        // セッションのセット
        dispatch(setSession(session));
        (async () => {
            // 定数のセット
            await dispatch(readConsts());
        })();

        // CSRFのセット
        const token = document.head.querySelector<HTMLMetaElement>(
            'meta[name="csrf-token"]'
        );
        if (token) {
            dispatch(setCSRF(token.content));
        }
    }, []);

    return (
        <ConnectedRouter history={history}>
            <Switch>
                <Route exact path={Url.TOP} component={ShopTop} />
                <Route exact path={Url.LOGIN} component={LoginForm} />
                <Route exact path={Url.REGISTER} component={RegisterForm} />
                <Route exact path={Url.PASSWORD_RESET} component={EMailForm} />
                <Route
                    path={`${Url.PASSWORD_RESET}/:id`}
                    component={ResetForm}
                />
                <Route exact path={Url.EMAIL_VERIFY} component={Verify} />
                <Route exact path={Url.CONTACT} component={ContactCreate} />
                <Route
                    exact
                    path={Url.CONTACT_COMPLETE}
                    component={ContactComplete}
                />

                {/* ★ログインユーザー専用ここから */}
                <AuthCheck session={session}>
                    <Route exact path={Url.HOME} component={Home} />
                    <Route exact path={Url.MYCART} component={MyCart} />
                    <Route
                        exact
                        path={Url.SHOP_COMPLETE}
                        component={ShopComplete}
                    />
                </AuthCheck>
                {/* ★ログインユーザー専用ここまで */}

                <Route component={NotFound} />
            </Switch>
        </ConnectedRouter>
    );
};

export default Router;
