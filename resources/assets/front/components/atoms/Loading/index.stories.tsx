import Loading from './index';
import type { Meta } from '@storybook/react';
import { JSX, useEffect } from 'react';
import useAppRoot from '@/states/useAppRoot';

export default {
  title: 'Components/Atoms/Loading',
  component: Loading,
  tags: ['autodocs'],
} as Meta<typeof Loading>;

export const Default: { render: () => JSX.Element } = {
  render: () => {
    const Component = () => {
      const { state, service } = useAppRoot();
      useEffect(() => {
        if (state) {
          service.showLoading();
        }
      }, [state, service]);
      if (!state) return <></>;
      return <Loading />;
    };
    return <Component />;
  },
};

export const HideLoading: { render: () => JSX.Element } = {
  render: () => {
    const Component = () => {
      const { state, service } = useAppRoot();
      useEffect(() => {
        if (state) {
          service.showLoading();
          setTimeout(() => {
            // 1秒後に消す
            service.hideLoading();
          }, 1000);
        }
      }, [state, service]);
      if (!state) return <></>;
      return <Loading />;
    };
    return <Component />;
  },
};
