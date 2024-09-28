import React, { FC, useState } from 'react'
import InputFormTemplate, {
  type InputFormTemplateProps,
} from '@/components/06_templates/InputFormTemplate'
import useAppRoot from '@/stores/useAppRoot'
import { useRouter } from 'next/router'
import { Api } from '@/constants/api'
import axios, { AxiosError } from '@/utils/axios'
import Loading from '@/components/01_atoms/Loading'
import Input from '@/components/01_atoms/Input'
import { useForm, type SubmitHandler } from 'react-hook-form'
import { useI18n } from '@/components/i18n'

type FormInputs = {
  email: string
}

const Index: FC = () => {
  const {
    query: { id: productId },
  } = useRouter()
  const main = useAppRoot()
  const { t } = useI18n('Common')

  const [loading, setLoading] = useState(false)
  const [isComplete, setIsComplete] = useState(false)
  const [errorMsg, setErrorMsg] = useState('')

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<FormInputs>()

  const validate = {
    email: {
      required: t('Please enter your email address'),
      pattern: {
        value: /[\w\-\\._]+@[\w\-\\._]+\.[A-Za-z]+/,
        message: t('Email address is correct'),
      },
    },
  }

  // フォーム送信ボタンを押された時の処理
  const onsubmit: SubmitHandler<FormInputs> = async ({ email }) => {
    try {
      setLoading(true)

      // 解約リクエストを送信する
      const {
        data: { message, error },
      } = await axios.post(Api.CancelRequest, {
        productId,
        email,
      })

      setIsComplete(true)
    } catch (e: unknown) {
      console.log(e)
      if (e instanceof AxiosError) {
        const { response } = e
        setErrorMsg(t(response?.data?.message))
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
    title: 'Product Cancellation Page',
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
              <p className="mb-4 leading-6">
                {t(
                  'We will send an e-mail with the URL of the cancellation page to the e-mail address you entered.'
                )}
              </p>
              <form onSubmit={handleSubmit(onsubmit)}>
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
                <div>
                  <button
                    className="bg-blue-500 rounded-md text-white border-none rounded-5 px-20 py-4 text-18 cursor-pointer mt-10"
                    type="submit"
                  >
                    {t('Send Email')}
                  </button>
                </div>
                {errorMsg && <p className="pt-4 text-red-500">{errorMsg}</p>}
                <Loading loading={loading} />
              </form>
            </>
          ) : (
            <p className="mb-4 leading-6">
              {t(
                'A link to the cancellation page has been sent to your e-mail address. Please follow the cancellation procedure from the URL provided in the email.'
              )}
            </p>
          )}
        </div>
      </section>
    </InputFormTemplate>
  )
}

export default Index
