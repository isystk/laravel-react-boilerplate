import { Meta, Story } from '@storybook/react'
import { Context } from '@/components/05_layouts/HtmlSkeleton'
import React from 'react'
import Header, { HeaderProps } from './index'
import MainService from '@/services/main'

export default {
  title: '04_organisms/Header',
  component: Header,
} as Meta

const Template: Story = () => {
  const main = new MainService(() => ({}))
  const props: HeaderProps = {
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
  if (!main) <></>
  return (
    <Context.Provider value={main}>
      <Header {...props} />
    </Context.Provider>
  )
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
