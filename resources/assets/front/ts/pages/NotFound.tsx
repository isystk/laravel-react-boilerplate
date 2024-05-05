import React, { FC } from "react";
import Layout from "@/components/Layout";
import MainService from "@/services/main";
import Box from "@/components/Box";

type Props = {
    appRoot: MainService;
};

const NotFound: FC<Props> = ({ appRoot }) => {
    return (
        <Layout appRoot={appRoot} title="Not Found">
            <main className="main">
                <Box title="Not Found">
                    <h1>お探しのページは見つかりません。</h1>
                </Box>
            </main>
        </Layout>
    );
};

export default NotFound;
