import { Meta, Story } from '@storybook/react'
import React from 'react'
import MonthInput, { MonthInputProps } from './index'
import { useForm } from 'react-hook-form'

export default {
  title: '01_atoms/MonthInput',
  component: MonthInput,
} as Meta

type FormMonthInputs = {
  yearMonth: string
}

const Template: Story = () => {
  const {
    control,
    register,
    formState: { errors },
  } = useForm<FormMonthInputs>()
  const validate = {
    yearMonth: {
      required: '処理年月を入力してください',
    },
  }
  const props: MonthInputProps = {
    name: 'yearMonth',
    placeholder: '処理年月',
    control,
    register,
    validate,
    errors,
  }
  return (
      <div className="w-56">
        <MonthInput {...props} />
      </div>
  )
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
