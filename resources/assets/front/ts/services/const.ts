import MainService from '@/services/main';
import { Api } from '@/constants/api';
import _ from 'lodash';

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

export default class ConstService {
  main: MainService;
  data: Consts;

  constructor(main: MainService) {
    this.main = main;
    this.data = initialState;
  }

  async readConsts() {
    const response = await fetch(Api.consts);
    const { consts } = await response.json();
    // APIで返却されるJSONとStoreに保存するオブジェクトのフォーマットが異なるので加工する
    this.data = _.mapKeys(consts.data, 'name');
    this.main.setAppRoot();
  }
}
