export type Carts = {
  stocks: CartStock[];
  message: string;
  email: string;
  count: number;
  sum: number;
};

export type CartStock = {
  id: number;
  stockId: number;
  name: string;
  detail: string;
  price: number;
  imageUrl: string;
};

const initialState: Carts = {
  stocks: [],
  message: '',
  email: '',
  count: 0,
  sum: 0,
};

export default class CartState {
  stocks: CartStock[];
  message: string;
  email: string;
  count: number;
  sum: number;

  constructor() {
    this.stocks = initialState.stocks;
    this.message = initialState.message;
    this.email = initialState.email;
    this.count = initialState.count;
    this.sum = initialState.sum;
  }
}
