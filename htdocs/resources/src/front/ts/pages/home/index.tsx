import React, { FC } from "react";
import SessionAlert from "@/components/elements/SessionAlert";
import Box from "@/components/Box";
import Layout from "@/components/Layout";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

const Home: FC<Props> = ({ appRoot }) => (
    <Layout appRoot={appRoot} title="ダッシュボード">
        <main className="main">
            <Box title="ダッシュボード">
                <SessionAlert target="status" />
                ログインが成功しました！
            </Box>
        </main>
    </Layout>
);
export default Home;
