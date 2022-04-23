import React from "react";
import { Alert } from "react-bootstrap";

const SessionAlert = props => {
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
