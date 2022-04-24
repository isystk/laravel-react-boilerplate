import React, { FC } from "react";
import Layout from "@/components/Layout";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

const NotFound: FC<Props> = ({ appRoot }) => {
    return (
        <Layout appRoot={appRoot}>
            <main className="main">
                <h1>Not Found</h1>;
            </main>
        </Layout>
    );
};

export default NotFound;
