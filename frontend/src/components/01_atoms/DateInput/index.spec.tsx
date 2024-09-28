import React from 'react'
import renderer from 'react-test-renderer'
import DateInput, { DateInputProps } from './index'
import { useForm } from 'react-hook-form'
import { renderHook } from '@testing-library/react-hooks'

type FormDateInputs = {
  birthday: string
}

describe('DateInput', () => {
  it('Match Snapshot', () => {
    const { result } = renderHook(() => useForm<FormDateInputs>())

    const {
      control,
      register,
      formState: { errors },
    } = result.current

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

    const component = renderer.create(<DateInput {...props} />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
