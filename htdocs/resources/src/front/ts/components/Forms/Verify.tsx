import React from "react";
import SessionAlert from "../Elements/SessionAlert";
import Box from "@/components/Box";
import Layout from "@/components/Layout";

const Verify = () => (
    <Layout>
        <main className="main">
            <Box title="メールアドレスを確認しました">
                <SessionAlert target="resent" />
                Before proceeding, please check your email for a verification
                link. If you did not receive the email,{" "}
                <a href="/email/resend">click here to request another</a>.
            </Box>
        </main>
    </Layout>
);

export default Verify;
