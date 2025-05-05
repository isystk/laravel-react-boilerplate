export interface KeyValue {
  key: number;
  value: string;
}

const initialState: ConstState = {
  gender: null,
  age: null,
};

export default class ConstState {
  gender: KeyValue[] | null;
  age: KeyValue[] | null;

  constructor() {
    this.gender = initialState.gender;
    this.age = initialState.age;
  }
}
