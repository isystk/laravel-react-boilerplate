import React from "react";
import SessionAlert from "../../components/Elements/SessionAlert";
import Box from "@/components/Box";
import Layout from "@/components/Layout";

const Home = () => (
    <Layout>
        <main className="main">
            <Box title="ダッシュボード">
                <SessionAlert target="status" />
                ログインが成功しました！
            </Box>
        </main>
    </Layout>
);
export default Home;
