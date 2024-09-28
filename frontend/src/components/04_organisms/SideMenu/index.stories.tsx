import { Meta, Story } from '@storybook/react'
import React from 'react'
import SideMenu, { SideMenuProps } from './index'

export default {
  title: '04_organisms/SideMenu',
  component: SideMenu,
} as Meta

const Template: Story = () => {
  const props: SideMenuProps = {
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
  return <SideMenu {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
