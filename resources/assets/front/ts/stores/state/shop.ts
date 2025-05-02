
export interface Stock {
  id: number;
  name: string;
  detail: string;
  price: number;
  imgpath: string;
  quantity: number;
  created_at: Date;
  updated_at: Date;
  isLike: boolean;
}

type Stocks = {
  current_page: number;
  total: number;
  data: Stock[];
};

const initialState = {
  current_page: 1,
  total: 0,
  data: [] as Stock[],
};

export default class ShopState {
  stocks: Stocks;

  constructor() {
    this.stocks = initialState;
  }

}
