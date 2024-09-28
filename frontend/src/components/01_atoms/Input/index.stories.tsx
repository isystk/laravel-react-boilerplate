import { Meta, Story } from '@storybook/react'
import React from 'react'
import Input, { InputProps } from './index'
import { useForm } from 'react-hook-form'

export default {
  title: '01_atoms/Input',
  component: Input,
} as Meta

type FormInputs = {
  email: string
}

const Template: Story = () => {
  const {
    register,
    formState: { errors },
  } = useForm<FormInputs>()
  const validate = {
    email: {
      required: 'メールアドレスを入力してください',
      pattern: {
        value: /[\w\-\\._]+@[\w\-\\._]+\.[A-Za-z]+/,
        message: 'メールアドレスを正しく入力してください',
      },
    },
  }
  const props: InputProps = {
    type: 'email',
    name: 'email',
    placeholder: 'メールアドレス',
    register,
    validate,
    errors,
  }
  return <Input {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
