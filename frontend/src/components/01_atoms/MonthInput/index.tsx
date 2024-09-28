import React, { FC } from 'react'
import { ContainerProps } from 'types'
import { connect } from '@/components/hoc'
import { Controller } from 'react-hook-form'
import MonthPickerInput from 'react-month-picker-input'
import 'react-month-picker-input/dist/react-month-picker-input.css'
import 'react-datepicker/dist/react-datepicker.css'
import * as styles from './styles'

/** MonthInputProps Props */
export type MonthInputProps = {
  small: boolean
  name
  placeholder: string
  value
  onChange
  control
  register
  validate
  errors
}
/** Presenter Props */
export type PresenterProps = MonthInputProps

const MonthInput: FC<PresenterProps> = ({
  small,
  placeholder,
  value,
  ...props
}) => (
  <MonthPickerInput
    lang="ja"
    // mode="calendarOnly" // 'normal', 'readOnly'
    monthFormat="long"
    i18n={{
      dateFormat: { ja: 'YYYY/MM' },
      monthNames: {
        ja: [
          '1月',
          '2月',
          '3月',
          '4月',
          '5月',
          '6月',
          '7月',
          '8月',
          '9月',
          '10月',
          '11月',
          '12月',
        ],
      },
    }}
    closeOnSelect
    inputProps={{
      placeholder,
      className: `${styles.monthInput} ${
        small ? 'p-2' : 'p-3'
      } bg-gray-200  border border-gray-200 rounded  leading-tight focus:outline-none focus:bg-white focus:border-gray-500`,
    }}
  />
)

/** Presenter Component */
const MonthInputPresenter: FC<PresenterProps> = ({
  name,
  onChange,
  control,
  register,
  validate,
  errors,
  ...props
}) => (
  <>
    {register ? (
      <Controller
        control={control}
        name={name}
        render={({ field: { onChange, value } }) => (
          <>
            <MonthInput
              {...props}
              {...register(name, validate[name])}
              value={value}
              onChange={(maskedValue, selectedYear, selectedMonth) => {
                onChange(maskedValue)
              }}
            />
            {errors[name] && (
              <span className="pt-4 text-red-500">{errors[name].message}</span>
            )}
          </>
        )}
      />
    ) : (
      <MonthInput
        {...props}
        onChange={(maskedValue, selectedYear, selectedMonth) => {
          console.log(maskedValue)
          onChange(maskedValue)
        }}
      />
    )}
  </>
)

/** Container Component */
const MonthInputContainer: React.FC<
  ContainerProps<MonthInputProps, PresenterProps>
> = ({ presenter, ...props }) => {
  return presenter({
    ...props,
  })
}

export default connect<MonthInputProps, PresenterProps>(
  'MonthInput',
  MonthInputPresenter,
  MonthInputContainer
)
