/**
 * Storeに保存するデータ型を定義
 */


export interface Parts
{
  isShowOverlay: boolean
}

export interface Auth
{
  auth: boolean;
  id?: number | null,
  name: string | null,
  email?: string,
  remember?: string,
  csrf?: string,
  request?: string,
  session?: string,
}

export interface Consts
{
  stripe_key?: Const
  gender?: Const
  age?: Const
}

export interface Const
{
  name: string
  data: KeyValue[] | string
}

export interface KeyValue
{
  key: number
  value: string
}

export interface Page
{
  total: number
  current_page: number
  last_page: number
  first_page_url: string
  prev_page_url: string
  next_page_url: string
  last_page_url: string
}

export interface Stocks
{
  data: Stock[]
  page?: Page
}

export interface Stock
{
  id: number
  name: string
  detail: string
  price: number
  imgpath: string
  quantity: number
  created_at: Date
  updated_at: Date
  isLike: boolean
}

export interface Likes
{
  data: string[]
}

export interface Carts
{
  data: Cart[]
  message: string
  username: string
  count: number
  sum: number
}

export interface Cart
{
  id: number
  name: string
  detail: string
  price: number
  imgpath: string
  quantity: number
  created_at: Date
  updated_at: Date
}
