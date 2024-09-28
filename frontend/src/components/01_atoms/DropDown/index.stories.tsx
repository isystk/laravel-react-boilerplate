import { Meta, Story } from '@storybook/react'
import React from 'react'
import DropDown, { DropDownProps } from './index'

export default {
  title: '01_atoms/DropDown',
  component: DropDown,
} as Meta

const Template: Story = () => {
  const props: DropDownProps = {
    label: 'サンプル太郎',
    items: [
      { label: 'リンク1', link: 'http://link1' },
      { label: 'リンク2', link: 'http://link2' },
      { label: 'リンク3', link: 'http://link3' },
    ],
  }
  return <DropDown {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
