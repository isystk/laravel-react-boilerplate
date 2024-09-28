import { Meta, Story } from '@storybook/react'
import React from 'react'
import Hamburger, { HamburgerProps } from './index'

export default {
  title: '01_atoms/Hamburger',
  component: Hamburger,
} as Meta

const Template: Story = ({ isMenuOpen }) => {
  const props: HamburgerProps = {
    isMenuOpen,
    setMenuOpen: (isMenuOpen) => console.log(isMenuOpen),
  }
  return <Hamburger {...props} />
}

export const Close = Template.bind()
Close.storyName = 'Close'
Close.args = { isMenuOpen: false }
export const Open = Template.bind()
Open.storyName = 'Open'
Open.args = { isMenuOpen: true }
