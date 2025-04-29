import React, { FC } from "react";
import SessionAlert from "@/components/elements/SessionAlert";
import BasicLayout from "@/components/templates/BasicLayout";

type Props = {
};

const Home: FC<Props> = () => (
    <BasicLayout title="ダッシュボード">
        <div className="bg-white p-6 rounded-md shadow-md">
            <SessionAlert target="status" />
            ログインが成功しました！
        </div>
    </BasicLayout>
);
export default Home;
