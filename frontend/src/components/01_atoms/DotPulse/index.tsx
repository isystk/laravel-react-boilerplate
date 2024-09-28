import React, { FC } from 'react'
import { ContainerProps } from 'types'
import { connect } from '@/components/hoc'
import * as styles from './styles'

/** DotPulseProps Props */
export type DotPulseProps = Record<never, never>
/** Presenter Props */
export type PresenterProps = DotPulseProps

/** Presenter Component */
const DotPulsePresenter: FC<PresenterProps> = ({ ...props }) => (
  <>
    <div className={styles.dotPulse}></div>
  </>
)

/** Container Component */
const DotPulseContainer: React.FC<
  ContainerProps<DotPulseProps, PresenterProps>
> = ({ presenter, ...props }) => {
  return presenter({
    ...props,
  })
}

export default connect<DotPulseProps, PresenterProps>(
  'DotPulse',
  DotPulsePresenter,
  DotPulseContainer
)
