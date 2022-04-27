import * as React from "react";
import { Navigate } from "react-router-dom";
import { FC } from "react";
import { Url } from "@/constants/url";
import { Session } from "@/services/auth";

type Props = {
    session: Session;
    component: React.ReactNode;
};

const AuthCheck: FC<Props> = ({ session, component }) => {
    // ログインしてなければログイン画面へとばす
    if (!session.id) {
        return <Navigate to={Url.LOGIN} />;
    }

    // 新規会員登録後、メール確認が未完了の場合
    if (!session.email_verified_at) {
        return <Navigate to={Url.EMAIL_VERIFY} />;
    }

    // ログイン済みの場合
    return <>{component}</>;
};

export default AuthCheck;
