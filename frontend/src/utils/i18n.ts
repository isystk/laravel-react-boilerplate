import type { InitOptions } from 'i18next'
import i18next from 'i18next'
import { initReactI18next } from 'react-i18next'
import LanguageDetector from 'i18next-browser-languagedetector'

import enCommon from '../locales/en/common.json'
import jaCommon from '../locales/ja/common.json'

const detector = new LanguageDetector(null, {
  order: ['navigator', 'localStorage'],
})

const option: InitOptions = {
  resources: {
    en: {
      Common: enCommon,
    },
    ja: {
      Common: jaCommon,
    },
  },
  fallbackLng: 'ja', // デフォルトのLocaleを日本語に設定
}

i18next.use(detector).use(initReactI18next).init(option)

export default i18next
