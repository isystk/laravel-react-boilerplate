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
      imageUrl: 'https://localhost/uploads/stock/makaron.jpg',
      price: 1000,
      detail: 'この商品の説明です。',
    };
    return <CartItem {...props} />;
  },
};
