import { beforeAll, vi } from 'vitest';
import { setProjectAnnotations } from '@storybook/react';
import * as projectAnnotations from './preview';
import '@testing-library/jest-dom';
import '@/i18n';

const project = setProjectAnnotations([projectAnnotations]);

beforeAll(() => {
  project.beforeAll?.();

  // コンソールログを抑制
  vi.spyOn(console, 'log').mockImplementation(() => {
    return;
  });
});
