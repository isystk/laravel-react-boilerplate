import * as React from "react";
import { Url } from "@/constants/url";
import Layout from "@/components/Layout";
import { FC } from "react";
import MainService from "@/services/main";
import { Link } from "react-router-dom";

type Props = {
    appRoot: MainService;
};

const ShopComplete: FC<Props> = ({ appRoot }) => {
    const auth = appRoot.auth;

    return (
        <Layout appRoot={appRoot} title="商品購入完了">
            <main className="main">
                <div className="contentsArea">
                    <h2
                        className="heading02"
                        style={{
                            color: "#555555",
                            fontSize: "1.2em",
                            padding: "24px 0px",
                        }}
                    >
                        {auth.name}
                        さんご購入ありがとうございました
                    </h2>
                    <div className="ta-center">
                        <p>
                            ご登録頂いたメールアドレスへ決済情報をお送りしております。お手続き完了次第商品を発送致します。
                            <br />
                            (メールは送信されません)
                        </p>
                        <Link to={Url.TOP} className="btn text-danger mt40">
                            商品一覧へ戻る
                        </Link>
                    </div>
                </div>
            </main>
        </Layout>
    );
};

export default ShopComplete;
