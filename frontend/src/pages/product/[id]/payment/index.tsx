import React, { FC } from 'react'
import InputFormTemplate, {
  type InputFormTemplateProps,
} from '@/components/06_templates/InputFormTemplate'
import useAppRoot from '@/stores/useAppRoot'
import useSWR from 'swr'
import { useRouter } from 'next/router'
import { loadStripe } from '@stripe/stripe-js'
import { STRIPE_KEY } from '@/constants'
import { Api } from '@/constants/api'
import axios from '@/utils/axios'
import { Elements } from '@stripe/react-stripe-js'
import CheckoutForm, {
  type ProductData,
} from '@/components/03_molecules/CheckoutForm'
import ErrorTemplate, {
  ErrorTemplateProps,
} from '@/components/06_templates/ErrorTemplate'

const stripePromise = loadStripe(STRIPE_KEY)

const Index: FC = () => {
  const {
    query: { id: productId, userKey },
  } = useRouter()
  const main = useAppRoot()

  const {
    data: product,
    error,
    isLoading,
  } = useSWR([`${Api.Product}`, productId], async ([url, productId]) => {
    const result = await axios.get(url, {
      params: {
        productId,
      },
    })
    if (0 === result.data.length) {
      return undefined
    }
    return { ...result.data[0] } as ProductData
  })

  if (isLoading) {
    // loading
    return <></>
  }
  if (!product) {
    const props: ErrorTemplateProps = { statusCode: 404 }
    return <ErrorTemplate {...props} />
  }

  if (!main) return <></>

  const props: InputFormTemplateProps = { main, title: product.name }
  return (
    <InputFormTemplate {...props}>
      <section className="max-w-800 mx-auto bg-white px-3 md:px-20 py-12 md:py-20 shadow-md text-center">
        <h2 className="text-2xl mb-12 md:mb-20">{product.name}</h2>
        <p className="text-18 text-left mb-12 md:mb-20">
          {product.description}
        </p>
        <>
          <Elements stripe={stripePromise}>
            <CheckoutForm product={product} userKey={userKey} />
          </Elements>
        </>
      </section>
    </InputFormTemplate>
  )
}

export default Index
