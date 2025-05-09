import type { Meta, StoryFn } from '@storybook/react';
import AuthCheck from './index';
import useAppRoot from '@/states/useAppRoot';
import { useEffect } from 'react';
import { User } from '@/states/auth';

export default {
  title: 'Components/Interactions/AuthCheck',
  component: AuthCheck,
  tags: ['autodocs'],
} as Meta<typeof AuthCheck>;

export const LoggedInVerified: StoryFn = () => {
  const Component = () => {
    const { state, service } = useAppRoot();
    useEffect(() => {
      service.auth.setUser({
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        email_verified_at: '2023-01-01T00:00:00Z',
      } as User);
    }, [service]);

    if (!state) {
      return <></>;
    }
    return <div>Protected Content</div>;
  };
  return <Component />;
};

export const NotLoggedIn: StoryFn = () => <div>Protected Content</div>;

export const NotVerified: StoryFn = () => {
  const Component = () => {
    const { state, service } = useAppRoot();
    useEffect(() => {
      service.auth.setUser({
        id: 2,
        name: 'Jane Doe',
        email: 'jane@example.com',
        email_verified_at: null,
      } as User);
    }, [service]);
    if (!state) {
      return <></>;
    }
    return <div>Protected Content</div>;
  };
  return <Component />;
};
