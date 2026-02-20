export interface KeyValue {
  key: number;
  value: string;
}

const initialState: ConstState = {
  contactType: null,
};

export default class ConstState {
  contactType: KeyValue[] | null;

  constructor() {
    this.contactType = initialState.contactType;
  }
}
