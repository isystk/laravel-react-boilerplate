import axios, { AxiosError, AxiosRequestConfig } from 'axios'

const getHeaders = (): AxiosRequestConfig['headers'] => {
  let acceptLanguage = 'ja'
  if (typeof window !== 'undefined') {
    acceptLanguage = localStorage.getItem('acceptLanguage') || 'ja'
  }
  return { 'Accept-Language': acceptLanguage }
}

const config: AxiosRequestConfig = {
  withCredentials: true,
  headers: getHeaders(),
}

const instance = axios.create(config)

export default instance
export { AxiosError }
