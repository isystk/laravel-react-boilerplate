import React, { FC } from "react";
import CommonHeader from "@/components/Commons/Header";
import CommonFooter from "@/components/Commons/Footer";
import Loading from "@/components/Commons/Loading";
import MainService from "@/services/main";
import { Helmet, HelmetProvider } from "react-helmet-async";

type Props = {
    appRoot: MainService;
    children: React.ReactNode;
    title: string;
};

const Layout: FC<Props> = ({ appRoot, children, title }) => {
    return (
        <HelmetProvider>
            <Helmet>
                <title>{title + " | LaraEC"}</title>
                <meta
                    name="description"
                    content="Laravel ＆ React.js の学習用サンプルアプリケーションです。"
                />
            </Helmet>
            <CommonHeader appRoot={appRoot} />
            {children}
            <CommonFooter />
            <Loading appRoot={appRoot} />
        </HelmetProvider>
    );
};

export default Layout;
