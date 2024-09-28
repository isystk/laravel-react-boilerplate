import '@/assets/sass/app.scss'
import { Provider } from 'react-redux'
import React, { useEffect } from 'react'
import { store } from '@/stores/appSlice'
import type { AppProps } from 'next/app'
import '@/utils/i18n'
import { initGA, logPageView } from '@/utils/analytics'
import { useRouter } from 'next/router'

const App: React.FC<AppProps> = ({ Component, pageProps }) => {
  const router = useRouter()

  useEffect(() => {
    initGA()
    logPageView()

    const acceptLanguage = navigator.language || navigator.userLanguage
    if (typeof window !== 'undefined') {
      localStorage.setItem('acceptLanguage', acceptLanguage)
    }

    router.events.on('routeChangeComplete', logPageView)
    return () => {
      router.events.off('routeChangeComplete', logPageView)
    }
  }, [router.events])

  return (
    <Provider store={store}>
      <Component {...pageProps} />
    </Provider>
  )
}

export default App
