import React from 'react'
import renderer from 'react-test-renderer'
import MonthInput, { MonthInputProps } from './index'
import { useForm } from 'react-hook-form'
import { renderHook } from '@testing-library/react-hooks'

type FormMonthInputs = {
  yearMonth: string
}

describe('MonthInput', () => {
  it('Match Snapshot', () => {
    const { result } = renderHook(() => useForm<FormMonthInputs>())

    const {
      control,
      register,
      formState: { errors },
    } = result.current

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

    const component = renderer.create(<MonthInput {...props} />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
