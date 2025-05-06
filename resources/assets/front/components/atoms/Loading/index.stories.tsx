import Loading from './index';
import type { Meta } from '@storybook/react';
import { JSX, useEffect } from 'react';
import useAppRoot from '@/states/useAppRoot';

export default {
  title: 'Components/Atoms/Loading',
  component: Loading,
  tags: ['autodocs'],
} as Meta<typeof Loading>;

export const Default: { render: () => null | JSX.Element } = {
  render: () => {
    const { state, service } = useAppRoot();
    useEffect(() => {
      if (state && service) {
        service.showLoading();
      }
    }, [state, service]);
    if (!state) return null;
    return <Loading />;
  },
};
