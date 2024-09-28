import { Meta, Story } from '@storybook/react'
import React from 'react'
import Logo, { LogoProps } from './index'

export default {
  title: '01_atoms/Logo',
  component: Logo,
} as Meta

const Template: Story = () => {
  const props: LogoProps = {
    name: 'sample',
    link: '#',
  }
  return <Logo {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
