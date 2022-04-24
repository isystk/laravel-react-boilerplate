import React, { FC } from "react";
import CommonHeader from "@/components/Commons/Header";
import CommonFooter from "@/components/Commons/Footer";
import Loading from "@/components/Commons/Loading";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
    children: React.ReactNode;
};

const Layout: FC<Props> = ({ appRoot, children }) => {
    return (
        <>
            <CommonHeader appRoot={appRoot} />
            {children}
            <CommonFooter />
            <Loading appRoot={appRoot} />
        </>
    );
};

export default Layout;
