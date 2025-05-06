import ErrorPage from './index';
import { JSX } from 'react';
import useAppRoot from '@/states/useAppRoot';

export default {
  title: 'Components/Organisms/ErrorPage',
  component: ErrorPage,
  tags: ['autodocs'],
};

export const Default500: { render: () => null | JSX.Element } = {
  render: () => {
    const { state } = useAppRoot();
    if (!state) return null;
    return <ErrorPage />;
  },
};

export const NotFound404: { render: () => null | JSX.Element } = {
  render: () => {
    const { state } = useAppRoot();
    if (!state) return null;
    return <ErrorPage status={404} />;
  },
};
