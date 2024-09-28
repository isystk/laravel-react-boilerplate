import React, { FC } from 'react'
import { ContainerProps, WithChildren } from 'types'
import { connect } from '@/components/hoc'
import * as styles from './styles'

/** CirclesProps Props */
export type CirclesProps = WithChildren
/** Presenter Props */
export type PresenterProps = CirclesProps

/** Presenter Component */
const CirclesPresenter: FC<PresenterProps> = ({ children, ...props }) => (
  <>
    <div className={styles.area}>
      <ul className={styles.circles}>
        <li className={styles.circlesLi1}></li>
        <li className={styles.circlesLi2}></li>
        <li className={styles.circlesLi3}></li>
        <li className={styles.circlesLi4}></li>
        <li className={styles.circlesLi5}></li>
        <li className={styles.circlesLi6}></li>
        <li className={styles.circlesLi7}></li>
        <li className={styles.circlesLi8}></li>
        <li className={styles.circlesLi9}></li>
        <li className={styles.circlesLi10}></li>
      </ul>
      <div className="relative">{children}</div>
    </div>
  </>
)

/** Container Component */
const CirclesContainer: React.FC<
  ContainerProps<CirclesProps, PresenterProps>
> = ({ presenter, children, ...props }) => {
  return presenter({
    children,
    ...props,
  })
}

export default connect<CirclesProps, PresenterProps>(
  'Circles',
  CirclesPresenter,
  CirclesContainer
)
