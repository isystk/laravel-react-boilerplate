import {useEffect, useState} from "react";
import { Elements } from "@stripe/react-stripe-js";
import { loadStripe } from "@stripe/stripe-js";
import BasicLayout from "@/components/templates/BasicLayout";
import useAppRoot from "@/stores/useAppRoot";
import CartItem from "@/components/molecules/CartItem";
import PaymentModal from "@/components/molecules/PaymentModal";

const MyCart = () => {
    const appRoot = useAppRoot();
    if (!appRoot) return <></>;

    const auth = appRoot.auth;
    const { carts } = appRoot.cart;
    const [isPaymentModalOpen, setIsPaymentModalOpen] = useState<boolean>(false);
    const stripePromise = loadStripe(appRoot.const.data.stripe_key?.data + "");

    useEffect(() => {
        (async () => {
            // マイカートデータを取得する
            await appRoot.cart.readCarts();
        })();
    }, []);

    return (
        <BasicLayout title="マイカート">
            <div className="bg-gray-100 p-6 rounded-md shadow-md ">
                <h2 className="text-center font-bold text-2xl">{auth.name}さんのカートの中身</h2>
                <div className="mt-10 ">
                    <p className="text-center">{carts.message}</p>
                    {(() => {
                        if (carts.data.length === 0) {
                            return (
                                <p className="text-center">カートに商品がありません。</p>
                            );
                        } else {
                            return (
                                <>
                                    <div className="flex flex-wrap">
                                        {carts.data.map((cart, index) => (
                                            <CartItem key={index} {...cart} />
                                        ))}
                                    </div>
                                    <div className="bg-white mt-10 p-10">
                                        <div className="w-50 m-auto">
                                            <p className="font-bold">合計個数：{carts.count}個</p>
                                            <p className="font-bold">合計金額：{carts.sum}円</p>
                                        </div>
                                        <div className="w-50 m-auto text-center">
                                            <button
                                                type="submit"
                                                className="btn btn-primary mt-5"
                                                onClick={() => {
                                                    setIsPaymentModalOpen(true);
                                                }}
                                            >
                                                決済をする
                                            </button>
                                        </div>
                                    </div>
                                    <Elements stripe={stripePromise}>
                                        <PaymentModal
                                            isOpen={isPaymentModalOpen}
                                            handleClose={() => {
                                                setIsPaymentModalOpen(false);
                                            }}
                                            amount={carts.sum}
                                        />
                                    </Elements>
                                </>
                            );
                        }
                    })()}
                </div>
            </div>
        </BasicLayout>
    );
};

export default MyCart;
