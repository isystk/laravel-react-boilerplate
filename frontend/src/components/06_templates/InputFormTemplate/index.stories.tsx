import { Meta, Story } from '@storybook/react'
import React from 'react'
import InputFormTemplate, { InputFormTemplateProps } from './index'
import MainService from '@/services/main'

export default {
  title: '06_templates/InputFormTemplate',
  component: InputFormTemplate,
} as Meta

const Template: Story = () => {
  const props: InputFormTemplateProps = { title: 'サンプル' }
  return <InputFormTemplate {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
