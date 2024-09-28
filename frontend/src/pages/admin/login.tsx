import React, { FC, useEffect, useState } from 'react'
import AdminTemplate, {
  type AdminTemplateProps,
} from '@/components/06_templates/AdminTemplate'
import { useRouter } from 'next/router'
import { Api } from '@/constants/api'
import Loading from '@/components/01_atoms/Loading'
import Input from '@/components/01_atoms/Input'
import { useForm, type SubmitHandler } from 'react-hook-form'
import { Url } from '@/constants/url'
import axios from '@/utils/axios'
import useAppRoot from '@/stores/useAppRoot'
import { useI18n } from '@/components/i18n'
import { AxiosError } from 'axios'

type FormInputs = {
  user: string
  password: string
}

const Index: FC = () => {
  const router = useRouter()
  const main = useAppRoot()
  const { t } = useI18n('Common')
  const [checkLoading, setCheckLoading] = useState(true)
  const [loading, setLoading] = useState(false)
  const [errorMsg, setErrorMsg] = useState('')

  useEffect(() => {
    ;(async () => {
      try {
        await main.loginCheck()
        // 認証済みの場合は、ホーム画面にリダイレクト
        await router.replace(Url.AdminHome)
      } catch (e: unknown) {
        // 未ログイン状態なので何もしない
      }
      setCheckLoading(false)
    })()
  }, [])

  const {
    register,
    control,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<FormInputs>()

  const validate = {
    userName: {
      required: t('Please enter your user name'),
    },
    password: {
      required: t('Please enter your password'),
    },
  }

  if (checkLoading) return <></>

  // フォーム送信ボタンを押された時の処理
  const onsubmit: SubmitHandler<FormInputs> = async ({
    userName,
    password,
  }) => {
    try {
      setLoading(true)

      const {
        data: { user },
      } = await axios.post(Api.Login, {
        user: userName,
        password,
      })

      if (user) {
        // 認証に成功したらHOME画面にリダイレクト
        location.href = Url.AdminHome
      }
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

  const props: AdminTemplateProps = { main, title: t('Login') as string }
  return (
    <AdminTemplate {...props}>
      <section className="max-w-800 mx-auto bg-white px-3 md:px-20 py-12 md:py-20 shadow-md text-center md:w-2/3 ">
        <h2 className="text-2xl mb-8 md:mb-10">{t('Login')}</h2>
        <div>
          <p className="mb-4 leading-6"></p>
          <form onSubmit={handleSubmit(onsubmit)}>
            <div className="flex flex-col mb-4">
              <Input
                {...{
                  placeholder: t('username'),
                  type: 'text',
                  name: 'userName',
                  register,
                  validate,
                  errors,
                }}
              />
            </div>
            <div className="flex flex-col mb-4">
              <Input
                {...{
                  placeholder: t('password'),
                  type: 'password',
                  name: 'password',
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
                {t('Log in')}
              </button>
            </div>
            {errorMsg && <p className="pt-4 text-red-500">{errorMsg}</p>}
            <Loading loading={loading} />
          </form>
        </div>
      </section>
    </AdminTemplate>
  )
}

export default Index
