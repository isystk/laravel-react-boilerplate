import React, { FC } from "react";
import SessionAlert from "@/components/elements/SessionAlert";
import Box from "@/components/Box";
import Layout from "@/components/Layout";
import MainService from "@/services/main";
import CSRFToken from "@/components/elements/CSRFToken";

type Props = {
    appRoot: MainService;
};

const Verify: FC<Props> = ({ appRoot }) => (
    <Layout appRoot={appRoot} title="メールを確認してください">
        <main className="main">
            <Box title="メールを確認してください">
                <SessionAlert target="resent" />
                確認用リンクが記載されたメールをご確認ください。メールが届いていない場合は{" "}
                <a
                    href="#"
                    onClick={(e) => {
                        e.preventDefault();
                        const form = document.getElementById(
                            "email-form"
                        ) as HTMLFormElement;
                        const evt = document.createEvent("Event");
                        evt.initEvent("submit", true, true);
                        if (form && form.dispatchEvent(evt)) {
                            form.submit();
                        }
                    }}
                >
                    こちら
                </a>{" "}
                から再度リクエストしてください。
                <form
                    id="email-form"
                    action="/email/verification-notification"
                    method="POST"
                    style={{ display: "none" }}
                >
                    <CSRFToken appRoot={appRoot} />
                </form>
            </Box>
        </main>
    </Layout>
);

export default Verify;
