import React, { FC } from "react";
import { Alert } from "reactstrap";

type Props = {
    target: string;
};

const SessionAlert: FC<Props> = (props) => {
    if (window.laravelSession[props.target] !== "") {
        return (
            <Alert color="success" role="alert">
                {props.target === "resent"
                    ? "あなたのメールアドレスに新しい認証リンクが送信されました。"
                    : window.laravelSession[props.target]}
            </Alert>
        );
    } else {
        return <></>;
    }
};

export default SessionAlert;
