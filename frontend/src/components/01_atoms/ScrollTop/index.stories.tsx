import { Meta, Story } from '@storybook/react'
import React from 'react'
import ScrollTop, { ScrollTopProps } from './index'

export default {
  title: '01_atoms/ScrollTop',
  component: ScrollTop,
} as Meta

const Template: Story = () => {
  const props: ScrollTopProps = {
    scrollY: 0,
  }
  return <ScrollTop {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
