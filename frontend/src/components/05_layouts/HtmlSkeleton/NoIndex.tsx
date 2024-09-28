import { connect } from '@/components/hoc'
import React from 'react'
import { ContainerProps } from 'types'

/** NoIndex Props */
export type NoIndexProps = Record<never, never>
export type PresenterProps = NoIndexProps

/** Presenter Component */
const Presenter: React.FC<PresenterProps> = () => <></>

/** Container Component */
const Container: React.FC<ContainerProps<NoIndexProps, PresenterProps>> = ({
  presenter,
  ...props
}) => {
  return presenter({ ...props })
}

/** NoIndex */
export default connect<NoIndexProps, PresenterProps>(
  'NoIndex',
  Presenter,
  Container
)
