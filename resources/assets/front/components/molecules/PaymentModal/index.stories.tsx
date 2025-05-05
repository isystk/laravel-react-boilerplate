import PaymentModal from './index';
import { JSX } from 'react';
import { AppProvider } from '@/states/AppContext';
import useAppRoot from '@/states/useAppRoot';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';
import { BrowserRouter } from 'react-router-dom'; // 追加

const stripePromise = loadStripe('pk_test_XXXXXXXXXXXXXXXXXXXXXXXX'); // ダミーの公開キー

export default {
  title: 'Components/Molecules/PaymentModal',
  component: PaymentModal,
  tags: ['autodocs'],
  decorators: [
    Story => (
      <BrowserRouter>
        <AppProvider>
          <Elements stripe={stripePromise}>
            <Story />
          </Elements>
        </AppProvider>
      </BrowserRouter>
    ),
  ],
};

export const Default: { render: () => null | JSX.Element } = {
  render: () => {
    const { state } = useAppRoot();
    if (!state) return null;
    return (
      <PaymentModal
        isOpen={true}
        handleClose={() => alert('閉じるボタンが押されました')}
        amount={3000}
      />
    );
  },
};
