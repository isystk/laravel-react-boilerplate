import CSRFToken from './index';
import type { Meta } from '@storybook/react';
import { JSX, useEffect } from 'react';
import { AppProvider } from '@/states/AppContext';
import useAppRoot from '@/states/useAppRoot';

export default {
  title: 'Components/Atoms/CSRFToken',
  component: CSRFToken,
  tags: ['autodocs'],
  decorators: [
    Story => (
      <AppProvider>
        <Story />
      </AppProvider>
    ),
  ],
} as Meta<typeof CSRFToken>;

export const Default: { render: () => null | JSX.Element } = {
  render: () => {
    const [state, service] = useAppRoot();
    useEffect(() => {
      if (state) {
        service.auth.setCSRF('csrf-token');
      }
    }, [state, service]);
    if (!state) return null;
    return <CSRFToken />;
  },
};
