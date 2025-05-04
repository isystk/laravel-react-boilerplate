import { describe, it, expect } from 'vitest';
import { render } from '@testing-library/react';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react'; // Storybookファイル名に合わせて変更してください

const { Default } = composeStories(stories);

describe('Loading component (Storybook)', () => {
  it('should render the Loading component when loading is shown', () => {
    render(<Default />);

    // 例: Loadingコンポーネント内に "Loading..." やスピナーなどが含まれていると仮定
    expect(document.querySelector('#loading')).toBeInTheDocument();
  });
});
