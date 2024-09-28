import { Meta, Story } from '@storybook/react'
import React from 'react'
import DotPulse from './index'

export default {
  title: '01_atoms/DotPulse',
  component: DotPulse,
} as Meta

const Template: Story = () => {
  return <DotPulse />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
