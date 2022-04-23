import React, { VFC } from "react";
import { Alert } from "react-bootstrap";

type Props = {
    target: string;
};

const SessionAlert: VFC<Props> = props => {
    if (window.laravelSession[props.target] !== "") {
        return (
            <Alert variant="success" role="alert">
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
