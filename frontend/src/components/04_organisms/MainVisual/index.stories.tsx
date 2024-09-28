import { Meta, Story } from '@storybook/react'
import React from 'react'
import MainVisual from './index'

export default {
  title: '04_organisms/MainVisual',
  component: MainVisual,
} as Meta

const Template: Story = () => {
  return <MainVisual />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
