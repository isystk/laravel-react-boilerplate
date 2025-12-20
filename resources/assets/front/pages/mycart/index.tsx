import useAppRoot from '@/states/useAppRoot';
import { useEffect, useState } from 'react';
import BasicLayout from '@/components/templates/BasicLayout';
import CartItem from '@/components/molecules/CartItem';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';
import PaymentModal from '@/components/molecules/PaymentModal';
import Env from '@/constants/env';

const stripePromise = loadStripe(Env.STRIPE_KEY);

const MyCart = () => {
  const { state, service } = useAppRoot();
  const [isPaymentModalOpen, setIsPaymentModalOpen] = useState(false);

  useEffect(() => {
    service.cart.readCarts();
  }, [service]);

  if (!state) return <></>;
  const { name } = state.auth;
  const { stocks, message, count, sum } = state.cart;

  return (
    <BasicLayout title="マイカート">
      <div className="bg-gray-100 p-6 rounded-md shadow-md">
        <h2 className="text-center font-bold text-2xl">{name}さんのカートの中身</h2>
        <div className="mt-10">
          <p className="text-center">{message}</p>

          {stocks.length === 0 ? (
            <p className="text-center">カートに商品がありません。</p>
          ) : (
            <>
              <div className="flex flex-wrap">
                {stocks.map(cartStock => (
                  <CartItem key={cartStock.id} {...cartStock} />
                ))}
              </div>
              <div className="bg-white mt-10 p-10">
                <div className="w-50 m-auto">
                  <p className="font-bold">合計個数：{count}個</p>
                  <p className="font-bold">合計金額：{sum}円</p>
                </div>
                <div className="w-50 m-auto text-center">
                  <button
                    type="button"
                    className="btn btn-primary mt-5"
                    onClick={() => setIsPaymentModalOpen(true)}
                  >
                    決済をする
                  </button>
                </div>
              </div>
              <Elements stripe={stripePromise}>
                <PaymentModal
                  isOpen={isPaymentModalOpen}
                  handleClose={() => setIsPaymentModalOpen(false)}
                  amount={sum}
                />
              </Elements>
            </>
          )}
        </div>
      </div>
    </BasicLayout>
  );
};

export default MyCart;
