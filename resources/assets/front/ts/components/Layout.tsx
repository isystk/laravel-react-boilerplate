import React, { FC } from "react";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
    children: React.ReactNode;
    title: string;
};

const Layout: FC<Props> = ({ appRoot, children, title }) => {
    return (
        <>
            <title>{title + " | LaraEC"}</title>
            <meta
                name="description"
                content="Laravel ＆ React.js の学習用サンプルアプリケーションです。"
            />
            {children}
        </>
    );
};

export default Layout;
