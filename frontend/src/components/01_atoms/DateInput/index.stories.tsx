import { Meta, Story } from '@storybook/react'
import React from 'react'
import DateInput, { DateInputProps } from './index'
import { useForm } from 'react-hook-form'

export default {
  title: '01_atoms/DateInput',
  component: DateInput,
} as Meta

type FormDateInputs = {
  birthday: string
}

const Template: Story = () => {
  const {
    control,
    register,
    formState: { errors },
  } = useForm<FormDateInputs>()
  const validate = {
    birthday: {
      required: '生年月日を入力してください',
    },
  }
  const props: DateInputProps = {
    name: 'birthday',
    placeholder: '生年月日',
    control,
    register,
    validate,
    errors,
  }
  return <DateInput {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
