import MainService from '@/services/main';
import { Api } from '@/constants/api';
import _ from 'lodash';
import ConstState from '@/state/const';

export default class ConstService {
  main: MainService;
  const: ConstState;

  constructor(main: MainService) {
    this.main = main;
    this.const = main.root.const;
  }

  async readConsts() {
    const response = await fetch(Api.consts);
    const { consts } = await response.json();
    // APIで返却されるJSONとStoreに保存するオブジェクトのフォーマットが異なるので加工する
    this.const.data = _.mapKeys(consts.data, 'name');
    this.main.setRootState();
  }
}
