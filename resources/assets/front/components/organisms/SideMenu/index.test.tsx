import { fireEvent, render } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default } = composeStories(stories);

describe('SideMenu Storybook Tests', () => {
  it('ハンバーガーボタンが表示されていること', () => {
    const { container } = render(<Default />);
    const menuBtn = container.querySelector('.menuBtn');
    expect(menuBtn).toBeInTheDocument();
    const sideMenu = container.querySelector('.sideMenu');
    expect(sideMenu).not.toHaveClass('open');
  });

  it('ハンバーガーボタンをクリックするとサイドメニューが表示されること', () => {
    const { container } = render(<Default />);
    const menuBtn = container.querySelector('.menuBtn') as HTMLElement;
    fireEvent.click(menuBtn);
    const sideMenu = container.querySelector('.sideMenu');
    expect(sideMenu).toHaveClass('open');
  });

  it('オーバーレイをクリックするとがサイドメニューが閉じること', () => {
    const { container } = render(<Default />);
    const menuBtn = container.querySelector('.menuBtn') as HTMLElement;
    fireEvent.click(menuBtn);
    const sideMenu = container.querySelector('.sideMenu');
    expect(sideMenu).toHaveClass('open');
    const overlay = container.querySelector('.overlay') as HTMLElement;
    fireEvent.click(overlay);
    expect(sideMenu).not.toHaveClass('open');
  });

  it('閉じるをクリックするとがサイドメニューが閉じること', () => {
    const { container } = render(<Default />);
    const menuBtn = container.querySelector('.menuBtn') as HTMLElement;
    fireEvent.click(menuBtn);
    const sideMenu = container.querySelector('.sideMenu');
    expect(sideMenu).toHaveClass('open');
    fireEvent.click(menuBtn);
    expect(sideMenu).not.toHaveClass('open');
  });
});
