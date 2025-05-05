import ErrorPage from './index';
import { JSX } from 'react';
import { BrowserRouter } from 'react-router-dom';
import { AppProvider } from '@/states/AppContext';
import useAppRoot from '@/states/useAppRoot';

export default {
  title: 'Components/Organisms/ErrorPage',
  component: ErrorPage,
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
