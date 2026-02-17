import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import LanguageDetector from 'i18next-browser-languagedetector';

import jaCommon from './locales/ja/common.json';
import jaAuth from './locales/ja/auth.json';
import jaContact from './locales/ja/contact.json';
import jaCart from './locales/ja/cart.json';
import jaProfile from './locales/ja/profile.json';
import jaError from './locales/ja/error.json';
import jaValidation from './locales/ja/validation.json';

import enCommon from './locales/en/common.json';
import enAuth from './locales/en/auth.json';
import enContact from './locales/en/contact.json';
import enCart from './locales/en/cart.json';
import enProfile from './locales/en/profile.json';
import enError from './locales/en/error.json';
import enValidation from './locales/en/validation.json';

import zhCommon from './locales/zh/common.json';
import zhAuth from './locales/zh/auth.json';
import zhContact from './locales/zh/contact.json';
import zhCart from './locales/zh/cart.json';
import zhProfile from './locales/zh/profile.json';
import zhError from './locales/zh/error.json';
import zhValidation from './locales/zh/validation.json';

const resources = {
  ja: {
    common: jaCommon,
    auth: jaAuth,
    contact: jaContact,
    cart: jaCart,
    profile: jaProfile,
    error: jaError,
    validation: jaValidation,
  },
  en: {
    common: enCommon,
    auth: enAuth,
    contact: enContact,
    cart: enCart,
    profile: enProfile,
    error: enError,
    validation: enValidation,
  },
  zh: {
    common: zhCommon,
    auth: zhAuth,
    contact: zhContact,
    cart: zhCart,
    profile: zhProfile,
    error: zhError,
    validation: zhValidation,
  },
};

i18n
  .use(LanguageDetector)
  .use(initReactI18next)
  .init({
    resources,
    fallbackLng: 'ja',
    defaultNS: 'common',
    ns: ['common', 'auth', 'contact', 'cart', 'profile', 'error', 'validation'],
    interpolation: {
      escapeValue: false,
    },
    detection: {
      order: ['localStorage'],
      lookupLocalStorage: 'i18n_language',
      caches: ['localStorage'],
    },
  });

export default i18n;
