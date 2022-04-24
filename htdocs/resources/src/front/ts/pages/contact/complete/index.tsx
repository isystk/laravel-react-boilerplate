import * as React from "react";
import { Url } from "@/constants/url";
import Layout from "@/components/Layout";
import { FC } from "react";
import { useDispatch } from "react-redux";
import { push } from "connected-react-router";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

const ContactComplete: FC<Props> = ({ appRoot }) => {
    const dispatch = useDispatch();
    return (
        <Layout appRoot={appRoot}>
            <main className="main">
                <div className="contentsArea">
                    <h2
                        className="heading02"
                        style={{
                            color: "#555555",
                            fontSize: "1.2em",
                            padding: "24px 0px"
                        }}
                    >
                        お問い合わせが完了しました。
                    </h2>

                    <div className="ta-center">
                        <p>
                            お問い合わせが完了しました。担当者から連絡があるまでお待ち下さい。
                        </p>
                        <a
                            href={Url.TOP}
                            className="btn text-danger mt40"
                            onClick={e => {
                                e.preventDefault();
                                dispatch(push(Url.TOP));
                            }}
                        >
                            商品一覧へ戻る
                        </a>
                    </div>
                </div>
            </main>
        </Layout>
    );
};

export default ContactComplete;
