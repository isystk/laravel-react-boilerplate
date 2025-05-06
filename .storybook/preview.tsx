import type { Preview } from '@storybook/react';

import '@/assets/styles/app.scss';
import { AppProvider } from '@/states/AppContext';
import { BrowserRouter } from 'react-router-dom';

export const decorators = [
  (Story) => (
    <AppProvider>
      <BrowserRouter>
        <Story />
      </BrowserRouter>
    </AppProvider>
  ),
];

export const parameters: Preview['parameters'] = {
  controls: {
    matchers: {
      color: /(background|color)$/i,
      date: /Date$/i,
    },
  },
};
