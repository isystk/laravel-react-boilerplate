import { Meta, Story } from '@storybook/react'
import React from 'react'
import LineButton, { LineButtonProps } from './index'

export default {
  title: '01_atoms/LineButton',
  component: LineButton,
} as Meta

const Template: Story = () => {
  const props: LineButtonProps = {
    link: '#',
    label: '友達に追加して質問してみる',
  }
  return <LineButton {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
