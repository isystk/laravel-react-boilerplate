import React, { FC, useState } from 'react'
import InputFormTemplate, {
  type InputFormTemplateProps,
} from '@/components/06_templates/InputFormTemplate'
import useAppRoot from '@/stores/useAppRoot'
import { useRouter } from 'next/router'
import { Api } from '@/constants/api'
import axios, { AxiosError } from '@/utils/axios'
import Loading from '@/components/01_atoms/Loading'
import useSWR from 'swr'
import ErrorTemplate, {
  ErrorTemplateProps,
} from '@/components/06_templates/ErrorTemplate'
import Modal from '@/components/02_interactions/Modal'
import { useI18n } from '@/components/i18n'

const Index: FC = () => {
  const {
    query: { id: productId, token: cancelToken },
  } = useRouter()
  const main = useAppRoot()
  const { t } = useI18n('Common')

  const [loading, setLoading] = useState(false)
  const [showModal, setShowModal] = useState(false)
  const [isComplete, setIsComplete] = useState(false)
  const [errorMsg, setErrorMsg] = useState('')

  const {
    data: subscriptions,
    error,
    isLoading,
  } = useSWR(
    [Api.CancelConfirm, productId, cancelToken],
    async ([url, productId, cancelToken]) => {
      const result = await axios.post(url, {
        productId,
        cancelToken,
      })
      return { ...result.data }
    }
  )

  if (isLoading) {
    return <></>
  }
  if (error) {
    const props: ErrorTemplateProps = { statusCode: 404 }
    return <ErrorTemplate {...props} />
  }
  const subscription = subscriptions[0]
  let currentPeriodEnd = ''
  if (subscription.current_period_end) {
    const date = new Date(subscription.current_period_end * 1000)
    currentPeriodEnd =
      date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate()
  }

  const cancelSubscription = async () => {
    try {
      setLoading(true)
      setShowModal(false)

      // call the backend to create subscription
      const {
        data: { message, error },
      } = await axios.post(Api.Cancel, {
        productId,
        cancelToken,
      })

      setIsComplete(true)
    } catch (e: unknown) {
      console.log(e)
      if (e instanceof AxiosError) {
        const { response } = e
        setErrorMsg(response?.data?.message)
      } else if (e instanceof Error) {
        setErrorMsg(e.message)
      }
    } finally {
      setLoading(false)
    }
  }

  if (!main) return <></>

  const props: InputFormTemplateProps = {
    main,
    title: t('Product Cancellation Page') as string,
  }
  return (
    <InputFormTemplate {...props}>
      <section className="max-w-800 mx-auto bg-white px-3 md:px-20 py-12 md:py-20 shadow-md text-center">
        <h2 className="text-2xl mb-8 md:mb-10">
          {t('Product Cancellation Page')}
        </h2>
        <div>
          {!isComplete ? (
            <>
              <p className="mb-4 leading-6">{`${currentPeriodEnd} ${t(
                'After that, you will not be able to use the service. If you like, please click Cancel'
              )}`}</p>
              <div>
                <button
                  className="bg-blue-500 rounded-md text-white border-none rounded-5 px-20 py-4 text-18 cursor-pointer mt-10"
                  onClick={() => setShowModal(true)}
                >
                  {t('Cancel')}
                </button>
                {errorMsg && <p className="pt-4 text-red-500">{errorMsg}</p>}
                <Loading loading={loading} />
              </div>
            </>
          ) : (
            <p className="mb-4 leading-6">
              {t(
                'The cancellation has been completed. We look forward to serving you again.'
              )}
            </p>
          )}
        </div>
      </section>
      <Modal
        isOpen={showModal}
        handleCancel={() => setShowModal(false)}
        handleAccept={() => cancelSubscription()}
        acceptLabel={t('Yes')}
        cancelLabel={t('No')}
      >
        <svg
          aria-hidden="true"
          className="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth="2"
            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
          ></path>
        </svg>
        <h3 className="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
          {t('I will cancel the contract. May I?')}
        </h3>
      </Modal>
    </InputFormTemplate>
  )
}

export default Index
