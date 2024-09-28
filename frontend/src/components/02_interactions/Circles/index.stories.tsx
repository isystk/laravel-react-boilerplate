import { Meta, Story } from '@storybook/react'
import React from 'react'
import Circles from './index'

export default {
  title: '02_interactions/Circles',
  component: Circles,
} as Meta

const Template: Story = () => {
  return (
    <Circles>
      <div
        style={{
          height: '600px',
        }}
      ></div>
    </Circles>
  )
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
