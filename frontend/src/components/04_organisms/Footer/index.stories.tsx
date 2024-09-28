import { Meta, Story } from '@storybook/react'
import React from 'react'
import Footer, { FooterProps } from './index'

export default {
  title: '04_organisms/Footer',
  component: Footer,
} as Meta

const Template: Story = () => {
  const props: FooterProps = {
    menuItems: [
      {
        label: 'Menu1',
        href: 'https://menu1',
      },
      {
        label: 'Menu2',
        href: 'https://menu2',
      },
      {
        label: 'Menu3',
        href: 'https://menu3',
        target: '_blank',
      },
    ],
  }
  return <Footer {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
