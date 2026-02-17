import type { Preview } from '@storybook/react';

import '@/assets/styles/app.scss';
import { AppProvider } from '@/states/AppContext';
import { MemoryRouter } from 'react-router-dom';
import { MINIMAL_VIEWPORTS } from '@storybook/addon-viewport';

// Storybook用のラッパー（BrowserRouterの代わりにMemoryRouterを使用）
export const decorators = [
  (Story) => (
    <AppProvider>
      <MemoryRouter>
        <Story />
      </MemoryRouter>
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
  // デフォルトのViewportを設定する
  viewport: {
    viewports: MINIMAL_VIEWPORTS,
    defaultViewport: 'responsive',
  },
};
