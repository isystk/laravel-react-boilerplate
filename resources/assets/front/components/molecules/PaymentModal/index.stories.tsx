import PaymentModal from './index';
import { JSX } from 'react';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';
import { Meta } from '@storybook/react';

const stripePromise = loadStripe('pk_test_XXXXXXXXXXXXXXXXXXXXXXXX'); // ダミーの公開キー

const meta = {
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
} as Meta<typeof PaymentModal>;
export default meta;

export const Default: { render: () => JSX.Element } = {
  render: () => (
    <PaymentModal
      isOpen={true}
      handleClose={() => alert('閉じるボタンが押されました')}
      amount={3000}
    />
  ),
};
