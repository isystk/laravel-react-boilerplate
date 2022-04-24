import * as React from "react";
import { Navigate } from "react-router-dom";
import { FC } from "react";
import { Url } from "@/constants/url";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
    component: React.ReactNode;
};

const AuthCheck: FC<Props> = ({ appRoot, component }) => {
    // ログインしてなければログイン画面へとばす
    if (!appRoot.auth.isLogined) {
        return <Navigate to={Url.LOGIN} />;
    }

    // 新規会員登録後、メール確認が未完了の場合
    if (appRoot.auth.email_verified_at === null) {
        return <Navigate to={Url.EMAIL_VERIFY} />;
    }

    // ログイン済みの場合
    return <>{component}</>;
};

export default AuthCheck;
