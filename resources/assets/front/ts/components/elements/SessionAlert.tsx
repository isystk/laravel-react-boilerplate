import React, { FC } from "react";

type Props = {
    target: string;
};

const SessionAlert: FC<Props> = (props) => {
    if (window.laravelSession[props.target] !== "") {
        return (
            <div color="success" role="alert">
                {props.target === "resent"
                    ? "あなたのメールアドレスに新しい認証リンクが送信されました。"
                    : window.laravelSession[props.target]}
            </div>
        );
    } else {
        return <></>;
    }
};

export default SessionAlert;
