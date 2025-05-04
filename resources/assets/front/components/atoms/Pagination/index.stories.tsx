import { useState } from 'react';
import type { Meta, StoryFn } from '@storybook/react';
import Pagination from './index';

export default {
  title: 'Components/Atoms/Pagination',
  component: Pagination,
  tags: ['autodocs'],
} as Meta<typeof Pagination>;

export const Default: StoryFn = () => {
  const [page, setPage] = useState(3);

  return (
    <Pagination
      activePage={page}
      totalItemsCount={100}
      itemsCountPerPage={10}
      pageRangeDisplayed={5}
      onChange={setPage}
    />
  );
};

export const FirstPage: StoryFn = () => {
  const [page, setPage] = useState(1);

  return (
    <Pagination
      activePage={page}
      totalItemsCount={100}
      itemsCountPerPage={10}
      pageRangeDisplayed={5}
      onChange={setPage}
    />
  );
};

export const LastPage: StoryFn = () => {
  const [page, setPage] = useState(10);

  return (
    <Pagination
      activePage={page}
      totalItemsCount={100}
      itemsCountPerPage={10}
      pageRangeDisplayed={5}
      onChange={setPage}
    />
  );
};
