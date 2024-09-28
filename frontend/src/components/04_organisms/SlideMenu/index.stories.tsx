import { Meta, Story } from '@storybook/react'
import React from 'react'
import SlideMenu, { SlideMenuProps } from './index'

export default {
  title: '04_organisms/SlideMenu',
  component: SlideMenu,
} as Meta

const Template: Story = () => {
  const props: SlideMenuProps = {
    isMenuOpen: true,
    setMenuOpen: () => ({}),
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
  return <SlideMenu {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
