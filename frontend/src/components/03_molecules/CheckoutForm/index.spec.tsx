import React from 'react'
import renderer from 'react-test-renderer'
import CheckoutForm, { CheckoutFormProps } from './index'
import { Elements } from '@stripe/react-stripe-js'
import { STRIPE_KEY } from '@/constants'
import { loadStripe } from '@stripe/stripe-js'
const stripePromise = loadStripe(STRIPE_KEY)

describe('CheckoutForm', () => {
  it('Match Snapshot', () => {
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
    const component = renderer.create(
      <Elements stripe={stripePromise}>
        <CheckoutForm {...props} />
      </Elements>
    )
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
