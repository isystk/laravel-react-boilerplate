import { Meta, Story } from '@storybook/react'
import React from 'react'
import News from './index'

export default {
  title: '04_organisms/News',
  component: News,
} as Meta

const Template: Story = () => {
  return <News />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
