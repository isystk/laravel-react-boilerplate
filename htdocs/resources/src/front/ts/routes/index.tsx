import React from "react";
import { Route, Switch } from "react-router";
import { URL } from "@/constants/url";

import ShopTop from "@/pages";
import MyCart from "@/pages/mycart";
import ShopComplete from "@/pages/complete";
import ContactCreate from "@/pages/contact";
import ContactComplete from "@/pages/contact/complete";
import AuthCheck from "@/components/Auths/AuthCheck";
import NotFound from "@/components/NotFound";
import LoginForm from "@/pages/login";
import RegisterForm from "@/pages/register";
import EMailForm from "@/pages/password/reset";
import ResetForm from "@/pages/password/reset/[id]";
import Verify from "@/pages/email/verify";
import Home from "@/pages/home";

const routes = (session: string) => {
    return (
        <Switch>
            <Route exact path={URL.TOP} component={ShopTop} />
            <Route exact path={URL.LOGIN} component={LoginForm} />
            <Route exact path={URL.REGISTER} component={RegisterForm} />
            <Route exact path={URL.PASSWORD_RESET} component={EMailForm} />
            <Route path={`${URL.PASSWORD_RESET}/:id`} component={ResetForm} />
            <Route exact path={URL.EMAIL_VERIFY} component={Verify} />
            <Route exact path={URL.CONTACT} component={ContactCreate} />
            <Route
                exact
                path={URL.CONTACT_COMPLETE}
                component={ContactComplete}
            />

            {/* ★ログインユーザー専用ここから */}
            <AuthCheck session={session}>
                <Route exact path={URL.HOME} component={Home} />
                <Route exact path={URL.MYCART} component={MyCart} />
                <Route
                    exact
                    path={URL.SHOP_COMPLETE}
                    component={ShopComplete}
                />
            </AuthCheck>
            {/* ★ログインユーザー専用ここまで */}

            <Route component={NotFound} />
        </Switch>
    );
};

export default routes;
