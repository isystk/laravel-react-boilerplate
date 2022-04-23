import React, { VFC } from "react";
import SessionAlert from "@/components/Elements/SessionAlert";
import Box from "@/components/Box";
import Layout from "@/components/Layout";

const Verify: VFC = () => (
    <Layout>
        <main className="main">
            <Box title="メールアドレスを確認してください">
                <SessionAlert target="resent" />
                確認用リンクが記載されたメールをご確認ください。メールが届いていない場合は{" "}
                <a href="/email/resend">こちら</a>{" "}
                から再度リクエストしてください。
            </Box>
        </main>
    </Layout>
);

export default Verify;
