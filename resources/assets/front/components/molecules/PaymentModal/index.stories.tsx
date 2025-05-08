import PaymentModal from './index';
import { JSX } from 'react';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

const stripePromise = loadStripe('pk_test_XXXXXXXXXXXXXXXXXXXXXXXX'); // ダミーの公開キー

export default {
  title: 'Components/Molecules/PaymentModal',
  component: PaymentModal,
  tags: ['autodocs'],
  decorators: [
    Story => (
      <Elements stripe={stripePromise}>
        <Story />
      </Elements>
    ),
  ],
};

export const Default: { render: () => JSX.Element } = {
  render: () => (
    <PaymentModal
      isOpen={true}
      handleClose={() => alert('閉じるボタンが押されました')}
      amount={3000}
    />
  ),
};
