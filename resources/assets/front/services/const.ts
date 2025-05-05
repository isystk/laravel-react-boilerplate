import MainService from '@/services/main';
import { Api } from '@/constants/api';
import _ from 'lodash';
import ConstState from '@/states/const';

export default class ConstService {
  main: MainService;
  const: ConstState;

  constructor(main: MainService) {
    this.main = main;
    this.const = main.root.const;
  }

  async readConsts() {
    const response = await fetch(Api.consts);
    const { data } = await response.json();
    Object.assign(this.const, data);
    this.main.setRootState();
  }
}
