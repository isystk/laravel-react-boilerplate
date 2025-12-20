import type { Meta, StoryObj } from '@storybook/react';
import Portal from './index';

const meta: Meta<typeof Portal> = {
  title: 'Components/Interactions/Portal',
  component: Portal,
};

export default meta;

type Story = StoryObj<typeof Portal>;

export const Default: Story = {
  render: () => (
    <Portal>
      <div
        data-testid="portal-content"
        style={{
          position: 'fixed',
          top: '20px',
          right: '20px',
          background: '#eee',
          padding: '1rem',
          zIndex: 9999,
        }}
      >
        Portal内に描画されたコンテンツ
      </div>
    </Portal>
  ),
};
