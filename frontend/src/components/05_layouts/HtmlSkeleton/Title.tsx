import { connect } from '@/components/hoc'
import React, { Fragment } from 'react'
import { ContainerProps } from 'types'

/** Title Props */
export type TitleProps = { children }
export type PresenterProps = TitleProps

/** Presenter Component */
const Presenter: React.FC<PresenterProps> = ({ children }) => (
  <Fragment>{children}</Fragment>
)

/** Container Component */
const Container: React.FC<ContainerProps<TitleProps, PresenterProps>> = ({
  presenter,
  ...props
}) => {
  return presenter({ ...props })
}

/** Title */
export default connect<TitleProps, PresenterProps>(
  'Title',
  Presenter,
  Container
)
