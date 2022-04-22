import * as React from "react";
import { URL } from "@/constants/url";
import { Auth, Carts, Consts } from "@/stores/StoreTypes";
import Layout from "@/components/Layout";
import { FC } from "react";
import { useDispatch, useSelector } from "react-redux";
import { push } from "connected-react-router";

type Props = {
    auth: Auth;
    push;
};

type IRoot = {
    auth: Auth;
    consts: Consts;
    carts: Carts;
};

const ShopComplete: FC<Props> = () => {
    const dispatch = useDispatch();
    const auth = useSelector<IRoot, Auth>(state => state.auth);

    return (
        <Layout>
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
                        {auth.name}
                        さんご購入ありがとうございました
                    </h2>
                    <div className="ta-center">
                        <p>
                            ご登録頂いたメールアドレスへ決済情報をお送りしております。お手続き完了次第商品を発送致します。
                            <br />
                            (メールは送信されません)
                        </p>
                        <a
                            href={URL.TOP}
                            className="btn text-danger mt40"
                            onClick={e => {
                                e.preventDefault();
                                dispatch(push(URL.TOP));
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

export default ShopComplete;
