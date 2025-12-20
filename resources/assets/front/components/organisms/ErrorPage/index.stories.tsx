import ErrorPage from './index';
import { JSX } from 'react';

export default {
  title: 'Components/Organisms/ErrorPage',
  component: ErrorPage,
  tags: ['autodocs'],
};

export const Default500: { render: () => JSX.Element } = {
  render: () => <ErrorPage />,
};

export const NotFound404: { render: () => JSX.Element } = {
  render: () => <ErrorPage status={404} />,
};
