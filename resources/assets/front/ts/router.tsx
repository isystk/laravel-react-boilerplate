import { FC, Suspense, useEffect } from "react";
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
import ShopTop from "@/pages";
import Verify from "@/pages/email/verify";
import { Url } from "@/constants/url";
import useAppRoot from "@/stores/useAppRoot";
import { Session } from "@/services/auth";

type Props = {
    session: Session;
};

const Router: FC<Props> = ({ session }) => {
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
                    <Route index element={<ShopTop />} />
                    <Route path={Url.LOGIN} element={<LoginForm />}/>
                    <Route path={Url.REGISTER} element={<RegisterForm />}/>
                    <Route path={Url.PASSWORD_RESET} element={<EMailForm />}/>
                    <Route path={`${Url.PASSWORD_RESET}/:id`} element={<ResetForm />}/>
                    <Route path={Url.EMAIL_VERIFY} element={<Verify />}/>
                    <Route path={Url.CONTACT} element={<ContactCreate />}/>
                    <Route path={Url.CONTACT_COMPLETE} element={<ContactComplete />}/>

                    {/* ★ログインユーザー専用ここから */}
                    <Route path={Url.HOME} element={<AuthCheck session={session} component={<Home />}/>}/>
                    <Route path={Url.MYCART} element={<AuthCheck session={session} component={<MyCart />}/>}/>
                    <Route path={Url.SHOP_COMPLETE} element={<AuthCheck session={session} component={<ShopComplete />}/>}/>
                    {/* ★ログインユーザー専用ここまで */}

                    <Route path="*" element={<NotFound />} />
                </Routes>
            </Suspense>
        </BrowserRouter>
    );
};

export default Router;
