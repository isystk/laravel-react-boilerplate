import { Meta, Story } from '@storybook/react'
import React from 'react'
import Loading, { LoadingProps } from './index'

export default {
  title: '01_atoms/Loading',
  component: Loading,
} as Meta

const Template: Story = () => {
  const props: LoadingProps = {
    loading: true,
  }
  return <Loading {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
