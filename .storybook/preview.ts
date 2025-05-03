import type { Preview } from '@storybook/react'

import '../resources/assets/front/ts/styles/app.scss'

const preview: Preview = {
  parameters: {
    controls: {
      matchers: {
       color: /(background|color)$/i,
       date: /Date$/i,
      },
    },
  },
};

export default preview;
