import * as React from "react";
import { Url } from "@/constants/url";
import Layout from "@/components/Layout";
import { FC } from "react";
import MainService from "@/services/main";
import { Link } from "react-router-dom";

type Props = {
    appRoot: MainService;
};

const ContactComplete: FC<Props> = ({ appRoot }) => {
    return (
        <Layout appRoot={appRoot} title="お問い合わせ完了">
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
                        お問い合わせが完了しました。
                    </h2>

                    <div className="ta-center">
                        <p>
                            お問い合わせが完了しました。担当者から連絡があるまでお待ち下さい。
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

export default ContactComplete;
