export interface Consts {
  stripe_key?: Const;
  gender?: Const;
  age?: Const;
}

export interface Const {
  name: string;
  data: KeyValue[] | string;
}

export interface KeyValue {
  key: number;
  value: string;
}

const initialState: Consts = {};

export default class ConstState {
  data: Consts;

  constructor() {
    this.data = initialState;
  }
}
