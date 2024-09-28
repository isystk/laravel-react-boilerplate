import React, { FC } from 'react'
import { ContainerProps, WithChildren } from 'types'
import { connect } from '@/components/hoc'
import * as styles from './styles'

/** LoadingProps Props */
export type LoadingProps = WithChildren & {
  loading: boolean
}
/** Presenter Props */
export type PresenterProps = LoadingProps

/** Presenter Component */
const LoadingPresenter: FC<PresenterProps> = ({ loading }) => (
  <>
    <div
      className={`${styles.loading} fixed top-0 left-0 w-full h-full z-30 ${
        loading ? '' : 'hidden'
      }`}
    >
      <div className="absolute bottom-0 right-0 p-8 flex justify-center">
        <div className="animate-spin h-10 w-10 border-4 border-gray-500 rounded-full border-t-transparent"></div>
      </div>
    </div>
  </>
)

/** Container Component */
const LoadingContainer: React.FC<
  ContainerProps<LoadingProps, PresenterProps>
> = ({ presenter, children, ...props }) => {
  return presenter({
    children,
    ...props,
  })
}

export default connect<LoadingProps, PresenterProps>(
  'Loading',
  LoadingPresenter,
  LoadingContainer
)
