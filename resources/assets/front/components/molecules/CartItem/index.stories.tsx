import CartItem from './index';
import { JSX } from 'react';

export default {
  title: 'Components/Molecules/CartItem',
  component: CartItem,
  tags: ['autodocs'],
};

export const Default: { render: () => JSX.Element } = {
  render: () => {
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
