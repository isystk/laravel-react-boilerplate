import { useRouter } from 'next/router'
import React, { FC, useEffect, useState } from 'react'
import { Url } from '@/constants/url'
import useAppRoot from '@/stores/useAppRoot'

function withAuth(Component: FC) {
  return function AuthenticatedComponent(props) {
    const router = useRouter()
    const main = useAppRoot()
    const [loading, setLoading] = useState(true)

    useEffect(() => {
      // 認証チェック
      ;(async () => {
        try {
          await main.loginCheck()
          setLoading(false)
        } catch (e: unknown) {
          // 認証が確認できない場合はログイン画面にリダイレクト
          await router.replace(Url.AdminLogin)
        }
      })()
    }, [])
    if (loading) return <></>

    return <Component {...props} />
  }
}

export { withAuth }
