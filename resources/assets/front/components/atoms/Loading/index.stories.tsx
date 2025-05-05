import Loading from './index';
import type { Meta } from '@storybook/react';
import { JSX, useEffect } from 'react';
import { AppProvider } from '@/states/AppContext';
import useAppRoot from '@/states/useAppRoot';

export default {
  title: 'Components/Atoms/Loading',
  component: Loading,
  tags: ['autodocs'],
  decorators: [
    Story => (
      <AppProvider>
        <Story />
      </AppProvider>
    ),
  ],
} as Meta<typeof Loading>;

export const Default: { render: () => null | JSX.Element } = {
  render: () => {
    const { state, service } = useAppRoot();
    useEffect(() => {
      if (state) {
        service.showLoading();
      }
    }, [state, service]);
    if (!state) return null;
    return <Loading />;
  },
};
