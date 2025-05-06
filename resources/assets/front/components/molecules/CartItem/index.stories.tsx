import CartItem from './index';
import { JSX } from 'react';
import useAppRoot from '@/states/useAppRoot';

export default {
  title: 'Components/Molecules/CartItem',
  component: CartItem,
  tags: ['autodocs'],
};

export const Default: { render: () => null | JSX.Element } = {
  render: () => {
    const { state } = useAppRoot();
    if (!state) return null;
    const props = {
      id: 1,
      name: 'テスト商品',
      imgpath: 'sample.jpg',
      price: 1000,
      detail: 'この商品の説明です。',
      key: 1,
    };
    return <CartItem {...props} />;
  },
};
