import { Meta, Story } from '@storybook/react'
import React from 'react'
import Image, { ImageProps } from './index'

export default {
  title: '01_atoms/Image',
  component: Image,
} as Meta

const Template: Story = () => {
  const props: ImageProps = {
    src: '/images/dummy_480x320.png',
    alt: 'dummy',
    zoom: true,
  }
  return (
    <div className="w-64 rounded-lg overflow-hidden">
      <Image {...props} />
    </div>
  )
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
