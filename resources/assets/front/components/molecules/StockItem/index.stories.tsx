import StockItem from './index';
import { JSX } from 'react';
import useAppRoot from '@/states/useAppRoot';

export default {
  title: 'Components/Molecules/StockItem',
  component: StockItem,
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
      price: 1500,
      detail: '商品の説明文が入ります。',
      quantity: 3,
      isLike: false,
    };

    return <StockItem {...props} />;
  },
};
