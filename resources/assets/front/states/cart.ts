export type Carts = {
  data: Cart[];
  message: string;
  username: string;
  count: number;
  sum: number;
};

export type Cart = {
  id: number;
  name: string;
  detail: string;
  price: number;
  imgpath: string;
  quantity: number;
  created_at: Date;
  updated_at: Date;
};

const initialState: Carts = {
  data: [],
  message: '',
  username: '',
  count: 0,
  sum: 0,
};

export default class CartState {
  data: Cart[];
  message: string;
  username: string;
  count: number;
  sum: number;

  constructor() {
    this.data = initialState.data;
    this.message = initialState.message;
    this.username = initialState.username;
    this.count = initialState.count;
    this.sum = initialState.sum;
  }
}
