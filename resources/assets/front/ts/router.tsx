import { Suspense, useEffect } from "react";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import AuthCheck from "@/components/AuthCheck";
import ContactComplete from "@/pages/contact/complete";
import ContactCreate from "@/pages/contact";
import EMailForm from "@/pages/password/reset";
import Home from "@/pages/home";
import LoginForm from "@/pages/login";
import MyCart from "@/pages/mycart";
import NotFound from "@/pages/NotFound";
import RegisterForm from "@/pages/register";
import ResetForm from "@/pages/password/reset/[id]";
import ShopComplete from "@/pages/complete";
import Top from "@/pages/top";
import Verify from "@/pages/email/verify";
import { Url } from "@/constants/url";
import useAppRoot from "@/stores/useAppRoot";
import { Session } from "@/services/auth";

type Props = {
    session: Session;
};

const Router = ({ session }: Props) => {
    const appRoot = useAppRoot();

    useEffect(() => {
        if (!appRoot) return;
        // セッションのセット
        appRoot.auth.setSession(session);

        (async () => {
            // 定数のセット
            await appRoot.const.readConsts();
        })();

        // CSRFのセット
        const token = document.head.querySelector<HTMLMetaElement>(
            'meta[name="csrf-token"]'
        );
        if (token) {
            appRoot.auth.setCSRF(token.content);
        }
    }, [appRoot]);

    if (!appRoot) return <></>;

    return (
        <BrowserRouter>
            <Suspense fallback={<p>Loading...</p>}>
                <Routes>
                    <Route index element={<Top />} />
                    <Route path={Url.login} element={<LoginForm />}/>
                    <Route path={Url.register} element={<RegisterForm />}/>
                    <Route path={Url.passwordReset} element={<EMailForm />}/>
                    <Route path={`${Url.passwordReset}/:id`} element={<ResetForm />}/>
                    <Route path={Url.emailVerify} element={<Verify />}/>
                    <Route path={Url.contact} element={<ContactCreate />}/>
                    <Route path={Url.contactComplete} element={<ContactComplete />}/>

                    {/* ★ログインユーザー専用ここから */}
                    <Route path={Url.home} element={<AuthCheck session={session} component={<Home />}/>}/>
                    <Route path={Url.myCart} element={<AuthCheck session={session} component={<MyCart />}/>}/>
                    <Route path={Url.payComplete} element={<AuthCheck session={session} component={<ShopComplete />}/>}/>
                    {/* ★ログインユーザー専用ここまで */}

                    <Route path="*" element={<NotFound />} />
                </Routes>
            </Suspense>
        </BrowserRouter>
    );
};

export default Router;
