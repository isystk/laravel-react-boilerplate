import { Meta, Story } from '@storybook/react'
import React from 'react'
import ScrollIn, { ScrollInProps } from './index'

export default {
  title: '02_interactions/ScrollIn',
  component: ScrollIn,
} as Meta

const Template: Story = () => {
  const props: ScrollInProps = {
    delay: '1s',
  }
  return (
    <ScrollIn {...props}>
      <div>Hello!</div>
    </ScrollIn>
  )
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
