import React, { FC, useEffect } from "react";
import { useDispatch } from "react-redux";
import { hideLoading } from "@/services/actions";
import CommonHeader from "@/components/Commons/Header";
import CommonFooter from "@/components/Commons/Footer";
import Loading from "@/components/Commons/Loading";

const Layout: FC = ({ children }) => {
    const dispatch = useDispatch();

    useEffect(() => {
        (async () => {
            // ローディングを非表示にする
            dispatch(hideLoading());
        })();
    }, []);

    return (
        <>
            <CommonHeader />
            {children}
            <CommonFooter />
            <Loading />
        </>
    );
};

export default Layout;
