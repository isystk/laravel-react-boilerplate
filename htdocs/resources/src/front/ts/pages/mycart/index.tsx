import * as React from "react";
import { Url } from "@/constants/url";
import { Elements, StripeProvider } from "react-stripe-elements";
import CheckoutForm from "@/components/Shops/CheckoutForm";
import Modal from "@/components/Commons/Modal";
import { Button } from "react-bootstrap";
import { Auth, Carts, Consts } from "@/stores/StoreTypes";

import {
    readCarts,
    removeCart,
    showOverlay,
    hideOverlay
} from "@/services/actions";
import { useDispatch, useSelector } from "react-redux";
import { push } from "connected-react-router";
import Layout from "@/components/Layout";
import { VFC, useEffect } from "react";

type IRoot = {
    auth: Auth;
    consts: Consts;
    carts: Carts;
};

const MyCart: VFC = () => {
    const dispatch = useDispatch();
    const auth = useSelector<IRoot, Auth>(state => state.auth);
    const stripe_key = useSelector<IRoot, string>(
        state => state.consts.stripe_key?.data + ""
    );
    const carts = useSelector<IRoot, Carts>(state => state.carts);

    useEffect(() => {
        // マイカートデータを取得する
        dispatch(readCarts());
        // オーバーレイを閉じる
        return () => {
            dispatch(hideOverlay());
        };
    }, []);

    const renderCarts = (): JSX.Element => {
        return (
            <>
                {carts.data.map((cart, index) => (
                    <div className="block01_item" key={index}>
                        <img
                            src={`/uploads/stock/${cart.imgpath}`}
                            alt=""
                            className="block01_img"
                        />
                        <p>{cart.name} </p>
                        <p className="c-red mb20">{cart.price}円 </p>
                        <input
                            type="button"
                            value="カートから削除する"
                            className="btn-01"
                            onClick={() => {
                                dispatch(removeCart(cart.id));
                            }}
                        />
                    </div>
                ))}
            </>
        );
    };

    return (
        <Layout>
            <main className="main">
                <div className="contentsArea">
                    <h2 className="heading02">{auth.name}さんのカートの中身</h2>

                    <div>
                        <p className="text-center mt20">{carts.message}</p>
                        <br />

                        {(() => {
                            if (carts.data.length === 0) {
                                return (
                                    <p className="text-center">
                                        カートに商品がありません。
                                    </p>
                                );
                            } else {
                                return (
                                    <>
                                        <div className="block01">
                                            {renderCarts()}
                                        </div>
                                        <div className="block02">
                                            <p>
                                                合計個数：
                                                {carts.count}個
                                            </p>
                                            <p
                                                style={{
                                                    fontSize: "1.2em",
                                                    fontWeight: "bold"
                                                }}
                                            >
                                                合計金額：
                                                {carts.sum}円
                                            </p>
                                        </div>
                                        <div
                                            style={{
                                                margin: "40px 15px",
                                                textAlign: "center"
                                            }}
                                        >
                                            <Button
                                                type="submit"
                                                variant="primary"
                                                onClick={e => {
                                                    e.preventDefault();
                                                    dispatch(showOverlay());
                                                }}
                                            >
                                                決済をする
                                            </Button>
                                        </div>
                                        <Modal>
                                            <StripeProvider apiKey={stripe_key}>
                                                <Elements>
                                                    <CheckoutForm
                                                        amount={carts.sum}
                                                        username={
                                                            carts.username
                                                        }
                                                    />
                                                </Elements>
                                            </StripeProvider>
                                        </Modal>
                                    </>
                                );
                            }
                        })()}

                        <p className="mt30 ta-center">
                            <a
                                href={Url.TOP}
                                className="text-danger btn"
                                onClick={e => {
                                    e.preventDefault();
                                    dispatch(push(Url.TOP));
                                }}
                            >
                                商品一覧へ戻る
                            </a>
                        </p>
                    </div>
                </div>
            </main>
        </Layout>
    );
};

export default MyCart;
