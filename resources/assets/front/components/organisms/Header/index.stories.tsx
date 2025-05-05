import Header from './index';
import { JSX, useEffect } from 'react';
import { AppProvider } from '@/states/AppContext';
import useAppRoot from '@/states/useAppRoot';
import { BrowserRouter } from 'react-router-dom';
import { type User } from '@/states/auth';

export default {
  title: 'Components/Organisms/Header',
  component: Header,
  tags: ['autodocs'],
  decorators: [
    Story => (
      <BrowserRouter>
        <AppProvider>
          <Story />
        </AppProvider>
      </BrowserRouter>
    ),
  ],
};

export const Default: { render: () => null | JSX.Element } = {
  render: () => {
    const { state } = useAppRoot();
    if (!state) return null;

    return <Header />;
  },
};

export const Login: { render: () => null | JSX.Element } = {
  render: () => {
    const { state, service } = useAppRoot();

    useEffect(() => {
      if (!service) return;
      service.auth.setUser({
        id: 1,
        name: 'ユーザー名',
        email: 'test@test.com',
        email_verified_at: '2020-01-01 00:00:00',
      } as User);
    }, [service]);

    if (!state || !service) return <></>;
    return <Header />;
  },
};
