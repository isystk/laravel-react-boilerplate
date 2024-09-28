import React, { FC } from 'react'
import { ContainerProps } from 'types'
import { connect } from '@/components/hoc'
import * as styles from './styles'

/** InputProps Props */
export type InputProps = {
  small: boolean
  type: 'text' | 'password' | 'email'
  name: string
  placeholder: string
  value
  onChange
  register
  validate
  errors
}
/** Presenter Props */
export type PresenterProps = InputProps

/** Presenter Component */
const InputPresenter: FC<PresenterProps> = ({
  small,
  name,
  value = '',
  onChange,
  register,
  validate,
  errors,
  ...props
}) => (
  <>
    {register ? (
      <>
        <input
          {...props}
          className={`${styles.input} ${
            small ? 'p-2' : 'p-3'
          } w-full bg-gray-200 rounded-md`}
          {...register(name, validate[name])}
        />
        {errors[name] && (
          <span className="pt-4 text-red-500">{errors[name].message}</span>
        )}
      </>
    ) : (
      <input
        {...props}
        className={`${styles.input} ${
          small ? 'p-2' : 'p-3'
        } w-full bg-gray-200 rounded-md`}
        value={value}
        onChange={(e) => onChange(e.target.value)}
      />
    )}
  </>
)

/** Container Component */
const InputContainer: React.FC<ContainerProps<InputProps, PresenterProps>> = ({
  presenter,
  ...props
}) => {
  return presenter({
    ...props,
  })
}

export default connect<InputProps, PresenterProps>(
  'Input',
  InputPresenter,
  InputContainer
)
