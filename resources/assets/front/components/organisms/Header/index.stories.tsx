import Header from './index';
import { JSX, useEffect } from 'react';
import useAppRoot from '@/states/useAppRoot';
import { type User } from '@/states/auth';

export default {
  title: 'Components/Organisms/Header',
  component: Header,
  tags: ['autodocs'],
};

export const Default: { render: () => JSX.Element } = {
  render: () => <Header />,
};

export const Login: { render: () => JSX.Element } = {
  render: () => {
    const Component = () => {
      const { state, service } = useAppRoot();

      useEffect(() => {
        service.auth.setUser({
          id: 1,
          name: 'ユーザー名',
          email: 'test@test.com',
          email_verified_at: '2020-01-01 00:00:00',
          avatar_url: null,
        } as User);
      }, [service]);

      if (!state) {
        return <></>;
      }

      return <Header />;
    };
    return <Component />;
  },
};
