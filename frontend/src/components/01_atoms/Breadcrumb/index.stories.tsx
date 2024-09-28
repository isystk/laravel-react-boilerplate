import { Meta, Story } from '@storybook/react'
import React from 'react'
import Breadcrumb, { BreadcrumbProps } from './index'

export default {
  title: '01_atoms/Breadcrumb',
  component: Breadcrumb,
} as Meta

const Template: Story = () => {
  const props: BreadcrumbProps = {
    items: [
      { label: 'リンク1', link: 'http://link1' },
      { label: 'リンク2', link: 'http://link2' },
      { label: 'リンク3' },
    ],
  }
  return <Breadcrumb {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
