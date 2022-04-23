import * as React from "react";
import { Redirect } from "react-router";
import { FC } from "react";
import { Session } from "@/app";

type Props = {
    session: Session;
};

const AuthCheck: FC<Props> = ({ session, children }) => {
    // ログインしてなければログイン画面へとばす
    if (session.id === undefined) {
        return <Redirect to="/login" />;
    }

    // TODO 新規会員登録後、メール確認が未完了の場合
    if (session.email_verified_at === null) {
        return <Redirect to="/email/verify" />;
    }

    // ログイン済みの場合
    return <>{children}</>;
};

export default AuthCheck;
