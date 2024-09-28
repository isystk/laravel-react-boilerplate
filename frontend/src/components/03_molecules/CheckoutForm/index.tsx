import React, { FC, useState } from 'react'
import { ContainerProps } from 'types'
import * as styles from './styles'
import { connect } from '@/components/hoc'
import Loading from '@/components/01_atoms/Loading'
import Input from '@/components/01_atoms/Input'
import { CardElement, useElements, useStripe } from '@stripe/react-stripe-js'
import { useForm, type SubmitHandler } from 'react-hook-form'
import axios, { AxiosError } from '@/utils/axios'
import { PaymentMethodResult } from '@stripe/stripe-js'
import { Api } from '@/constants/api'
import * as stripe from 'stripe'
import { useI18n } from '@/components/i18n'

const CARD_ELEMENT_OPTIONS = {
  hidePostalCode: true, // 郵便番号を非表示
  style: {
    base: {
      fontFamily: 'Sans Serif',
      fontStyle: 'normal',
      fontWeight: '400',
      fontSize: '16px',
      letterSpacing: '1px',
      color: '#333333',
      '::placeholder': {
        color: '#a9a9a9',
      },
    },
  },
}

type FormInputs = {
  name: string
  email: string
  planId: string
}

export type ProductData = {
  plans: stripe.Plan[]
} & stripe.Product

/** CheckoutFormProps Props */
export type CheckoutFormProps = {
  product: ProductData
  userKey?: string
}
/** Presenter Props */
export type PresenterProps = CheckoutFormProps & {
  t
  onsubmit
  product
  isComplete
  loading
  cardErrorMsg
  errorMsg
  register
  handleSubmit
  errors
  stripe
}

/** Presenter Component */
const CheckoutFormPresenter: FC<PresenterProps> = ({
  t,
  onsubmit,
  product,
  isComplete,
  loading,
  cardErrorMsg,
  errorMsg,
  register,
  handleSubmit,
  errors,
  validate,
  stripe,
  planId,
  setPlanId,
  ...props
}) => (
  <>
    <div>
      {!isComplete ? (
        <form onSubmit={handleSubmit(onsubmit)} className={styles.checkoutForm}>
          <div className="flex flex-wrap items-center md:mb-8">
            {product.plans.map(({ id, amount, currency, interval }, idx) => {
              const locale = navigator.language
              const amountFmt = amount
                ? new Intl.NumberFormat(locale, {
                    style: 'currency',
                    currency,
                  }).format(amount)
                : ''
              return (
                <label
                  key={id}
                  className={`${
                    planId === id ? 'selected' : ''
                  } w-full md:w-1/2`}
                  onClick={() => setPlanId(id)}
                >
                  <div className="border text-center rounded-lg md:mr-4 py-12 h-72 mb-8">
                    <div className="mb-12">
                      {interval === 'month'
                        ? t('monthly amount')
                        : interval === 'year'
                        ? t('yearly amount')
                        : 'その他'}
                    </div>
                    <div className="font-bold text-5xl mb-12">{amountFmt}</div>
                    <input
                      type="radio"
                      value={id}
                      {...register('planId', validate['planId'])}
                    />
                  </div>
                </label>
              )
            })}
            {errors.planId && (
              <span className="pt-4 text-red-500">{errors.planId.message}</span>
            )}
          </div>
          <div className="flex flex-col mb-4">
            <Input
              {...{
                placeholder: t('your name'),
                type: 'text',
                name: 'name',
                register,
                validate,
                errors,
              }}
            />
          </div>
          <div className="flex flex-col mb-4">
            <Input
              {...{
                placeholder: t('Email address'),
                type: 'email',
                name: 'email',
                register,
                validate,
                errors,
              }}
            />
          </div>
          <div className="p-4 bg-gray-200 rounded-md">
            <CardElement options={CARD_ELEMENT_OPTIONS} />
            {cardErrorMsg && (
              <span className="pt-4 text-red-500">{cardErrorMsg}</span>
            )}
          </div>
          <div>
            <button
              className="bg-blue-500 rounded-md text-white border-none rounded-5 px-20 py-4 text-18 cursor-pointer mt-10"
              type="submit"
              disabled={!stripe}
            >
              {t('Buy Now')}
            </button>
          </div>
          {errorMsg && <p className="pt-4 text-red-500">{errorMsg}</p>}
        </form>
      ) : (
        <p className="text-18 text-left mb-20">
          {t('The purchase has been completed.')}
        </p>
      )}
      <Loading loading={loading} />
    </div>
  </>
)

/** Container Component */
const CheckoutFormContainer: React.FC<
  ContainerProps<CheckoutFormProps, PresenterProps>
> = ({ presenter, product, userKey, ...props }) => {
  const { t } = useI18n('Common')

  const [isComplete, setIsComplete] = useState(false)
  const [loading, setLoading] = useState(false)
  const [cardErrorMsg, setCardErrorMsg] = useState('')
  const [errorMsg, setErrorMsg] = useState('')
  const stripe = useStripe()
  const elements = useElements()
  const [planId, setPlanId] = useState(product.plans[0].id)

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<FormInputs>()

  const validate = {
    planId: {
      required: t('Plan Required'),
    },
    name: {
      required: t('Please enter your name'),
      max: {
        value: 30,
        message: t('Please enter your name in 30 characters or less'),
      },
    },
    email: {
      required: t('Please enter your email address'),
      pattern: {
        value: /[\w\-\\._]+@[\w\-\\._]+\.[A-Za-z]+/,
        message: t('Email address is correct'),
      },
    },
  }

  // フォーム送信ボタンを押された時の処理
  const onsubmit: SubmitHandler<FormInputs> = async ({
    planId,
    name,
    email,
  }) => {
    try {
      // create a payment method
      const payment: PaymentMethodResult | undefined =
        await stripe?.createPaymentMethod({
          type: 'card',
          card: elements?.getElement(CardElement),
          billing_details: {
            email,
          },
        })
      if (!payment) {
        setCardErrorMsg(t('Please enter your card information') as string)
        return
      }
      if (payment.error) {
        setCardErrorMsg(
          payment.error.message ?? (t('Invalid card information') as string)
        )
        return
      }
      setLoading(true)

      // 決済処理をする
      const { data } = await axios.post(Api.Payment, {
        userKey: userKey ?? email,
        paymentMethod: payment.paymentMethod?.id,
        name,
        email,
        planId,
      })
      reset()

      setIsComplete(true)
    } catch (e: unknown) {
      console.log(e)
      if (e instanceof AxiosError) {
        const { response } = e
        setErrorMsg(t(response?.data?.message) as string)
      } else if (e instanceof Error) {
        setErrorMsg(e.message)
      }
    } finally {
      setLoading(false)
    }
  }

  return presenter({
    t,
    onsubmit,
    product,
    isComplete,
    loading,
    cardErrorMsg,
    errorMsg,
    register,
    handleSubmit,
    errors,
    validate,
    stripe,
    planId,
    setPlanId,
    ...props,
  })
}

export default connect<CheckoutFormProps, PresenterProps>(
  'CheckoutForm',
  CheckoutFormPresenter,
  CheckoutFormContainer
)
