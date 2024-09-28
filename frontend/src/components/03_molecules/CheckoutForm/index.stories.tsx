import { Meta, Story } from '@storybook/react'
import React from 'react'
import CheckoutForm, { CheckoutFormProps } from './index'
import { Elements } from '@stripe/react-stripe-js'
import { STRIPE_KEY } from '@/constants'
import { loadStripe } from '@stripe/stripe-js'
const stripePromise = loadStripe(STRIPE_KEY)

export default {
  title: '03_molecules/CheckoutForm',
  component: CheckoutForm,
} as Meta

const Template: Story = () => {
  const props: CheckoutFormProps = {
    product: {
      plans: [
        {
          id: 1,
          amount: 999,
          currency: 'jpy',
        },
      ],
    },
  }
  return (
    <Elements stripe={stripePromise}>
      <CheckoutForm {...props} />
    </Elements>
  )
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
