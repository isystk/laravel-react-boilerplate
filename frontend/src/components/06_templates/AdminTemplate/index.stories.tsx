import { Meta, Story } from '@storybook/react'
import React from 'react'
import AdminTemplate, { AdminTemplateProps } from './index'
import MainService from '@/services/main'

export default {
  title: '06_templates/AdminTemplate',
  component: AdminTemplate,
} as Meta

const Template: Story = () => {
  const main = new MainService(() => ({}))
  const props: AdminTemplateProps = { main, title: 'サンプル' }
  return <AdminTemplate {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
