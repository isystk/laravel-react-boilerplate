import ProductService from '@/services/product'
import axios from '@/utils/axios'
import { Api } from '@/constants/api'

export type User = {
  userName: string
}

export default class MainService {
  _setAppRoot: (main: MainService) => void
  user?: User
  product: ProductService

  constructor(setAppRoot: (appRoot: MainService) => void) {
    this._setAppRoot = setAppRoot
    this.product = new ProductService(this)
  }

  setAppRoot() {
    this._setAppRoot(this)
  }

  async loginCheck() {
    try {
      const result = await axios.post(Api.LoginCheck)
      this.user = { ...result.data } as User
    } catch (e: unknown) {
      // 未ログイン状態なので何もしない
      this.user = undefined
      throw e
    }
  }

  async logout() {
    try {
      await axios.post(Api.Logout)
      this.user = undefined
    } catch (e: unknown) {
      this.user = undefined
      throw e
    }
  }
}
