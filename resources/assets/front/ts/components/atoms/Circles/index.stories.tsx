import type { Meta, StoryObj } from '@storybook/react';

import Circles from './index';

// More on how to set up stories at: https://storybook.js.org/docs/writing-stories#default-export
const meta = {
    title: 'Components/Atoms/Circles',
    component: Circles,
    parameters: {
        layout: 'centered',
    },
    tags: ['autodocs'],
    argTypes: {
        children: {
            control: 'text',
            description: 'Content inside the Circles component',
        },
    },
    args: {
        children: 'Sample Content',
    },
} satisfies Meta<typeof Circles>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
    args: {
        children: <div style={{ width: '1200px', height: '1000px', backgroundColor: 'black', color: 'white', padding: '20px' }}>Contents</div>,
    },
};
