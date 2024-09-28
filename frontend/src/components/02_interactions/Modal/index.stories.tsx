import { Meta, Story } from '@storybook/react'
import React from 'react'
import Modal, { ModalProps } from './index'
export default {
  title: '02_interactions/Modal',
  component: Modal,
} as Meta

const Template: Story = () => {
  const props: ModalProps = {
    isOpen: true,
  }
  return (
    <Modal {...props}>
      <svg
        aria-hidden="true"
        className="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        ></path>
      </svg>
      <h3 className="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
        削除します。よろしいですか？
      </h3>
    </Modal>
  )
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
