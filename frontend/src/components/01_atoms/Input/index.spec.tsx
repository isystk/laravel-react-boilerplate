import React from 'react'
import renderer from 'react-test-renderer'
import Input from './index'
import { useForm } from 'react-hook-form'
import { renderHook } from '@testing-library/react-hooks'

type FormInputs = {
  email: string
}

describe('Input', () => {
  it('Match Snapshot', () => {
    const { result } = renderHook(() => useForm<FormInputs>())

    const {
      register,
      formState: { errors },
    } = result.current

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

    const component = renderer.create(<Input {...props} />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
