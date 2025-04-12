import * as React from "react";
import { Url } from "@/constants/url";
import PaymentModal from "@/components/widgets/PaymentModal";
import { Button } from "reactstrap";
import Layout from "@/components/Layout";
import { FC, useEffect, useState } from "react";
import MainService from "@/services/main";
import { Link } from "react-router-dom";
import { Elements } from "@stripe/react-stripe-js";
import { loadStripe } from "@stripe/stripe-js";

type Props = {
    appRoot: MainService;
};

const MyCart: FC<Props> = ({ appRoot }) => {
    const auth = appRoot.auth;
    const { carts } = appRoot.cart;
    const [isOpen, setIsOpen] = useState<boolean>(false);
    const stripePromise = loadStripe(appRoot.const.data.stripe_key?.data + "");

    useEffect(() => {
        (async () => {
            // マイカートデータを取得する
            await appRoot.cart.readCarts();
        })();
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
                            onClick={async () => {
                                const result = await appRoot.cart.removeCart(
                                    cart.id
                                );
                                if (result) {
                                    await appRoot.cart.readCarts();
                                }
                            }}
                        />
                    </div>
                ))}
            </>
        );
    };

    return (
        <Layout appRoot={appRoot} title="マイカート">
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
                                                    fontWeight: "bold",
                                                }}
                                            >
                                                合計金額：
                                                {carts.sum}円
                                            </p>
                                        </div>
                                        <div
                                            style={{
                                                margin: "40px 15px",
                                                textAlign: "center",
                                            }}
                                        >
                                            <Button
                                                type="submit"
                                                color="primary"
                                                onClick={() => {
                                                    setIsOpen(true);
                                                }}
                                            >
                                                決済をする
                                            </Button>
                                        </div>
                                        <Elements stripe={stripePromise}>
                                            <PaymentModal
                                                isOpen={isOpen}
                                                handleClose={() => {
                                                    setIsOpen(false);
                                                }}
                                                appRoot={appRoot}
                                                amount={carts.sum}
                                            />
                                        </Elements>
                                    </>
                                );
                            }
                        })()}

                        <p className="mt30 ta-center">
                            <Link to={Url.TOP} className="text-danger btn">
                                商品一覧へ戻る
                            </Link>
                        </p>
                    </div>
                </div>
            </main>
        </Layout>
    );
};

export default MyCart;
