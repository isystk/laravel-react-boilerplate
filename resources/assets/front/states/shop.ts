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

const initialState = {
  current_page: 1,
  total: 0,
  data: [] as Stock[],
};

export default class ShopState {
  current_page: number;
  total: number;
  data: Stock[];

  constructor() {
    this.current_page = initialState.current_page;
    this.total = initialState.total;
    this.data = initialState.data;
  }
}
