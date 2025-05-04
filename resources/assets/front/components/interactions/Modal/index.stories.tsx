import { JSX, useState } from 'react';
import Modal from './index';
import type { Meta, StoryFn } from '@storybook/react';

export default {
  title: 'Components/Interactions/Modal',
  component: Modal,
  tags: ['autodocs'],
} as Meta<typeof Modal>;

export const Default: StoryFn = () => {
  const [isOpen, setIsOpen] = useState(false);
  return (
    <>
      <button className="btn btn-primary" onClick={() => setIsOpen(true)}>
        Open Modal
      </button>
      <Modal isOpen={isOpen} onClose={() => setIsOpen(false)}>
        <div>モーダルの中身</div>
      </Modal>
    </>
  );
};

export const Small: () => JSX.Element = () => {
  const [isOpen, setIsOpen] = useState(false);
  return (
    <>
      <button className="btn btn-primary" onClick={() => setIsOpen(true)}>
        Open Small Modal
      </button>
      <Modal isOpen={isOpen} onClose={() => setIsOpen(false)} small>
        <div>小さなモーダル</div>
      </Modal>
    </>
  );
};
