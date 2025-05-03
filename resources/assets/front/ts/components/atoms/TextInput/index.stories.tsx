import type { Meta, StoryObj } from '@storybook/react';
import TextInput from './index';

const meta: Meta<typeof TextInput> = {
  title: 'Form/TextInput',
  component: TextInput,
  tags: ['autodocs'],
  args: {
    identity: 'email',
    controlType: 'text',
    label: 'メールアドレス',
    required: true,
    autoComplete: 'email',
    className: 'mb-4',
  },
};
export default meta;

type Story = StoryObj<typeof TextInput>;

export const Default: Story = {
  args: {
    value: '',
    onChange: (e) => console.log('Change:', e.target.value),
  },
};

export const WithError: Story = {
  args: {
    value: '',
    error: '入力に誤りがあります',
    onChange: (e) => console.log('Change:', e.target.value),
  },
};

export const LaravelErrorSimulated: Story = {
  render: (args) => {
    // Laravelエラーをモックとして注入（本番ではwindow経由）
    if (typeof window !== 'undefined') {
      (window as any).laravelErrors = {
        [args.identity]: ['Laravel 側のエラーメッセージ'],
      };
    }
    return <TextInput {...args} />;
  },
  args: {
    value: '',
  },
};
