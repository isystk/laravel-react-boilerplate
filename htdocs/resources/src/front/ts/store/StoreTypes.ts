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
  name?: string
  data?: Const[]
}

export interface Const
{
  code: number
  text: string
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
  page: Page
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
}

export interface Likes
{
  data: string[]
}
