import React, { FC } from 'react'
import { ContainerProps, WithChildren } from 'types'
import { connect } from '@/components/hoc'
import * as styles from './styles'

/** LineButtonProps Props */
export type LineButtonProps = { link; label }
/** Presenter Props */
export type PresenterProps = LineButtonProps

/** Presenter Component */
const LineButtonPresenter: FC<PresenterProps> = ({
  link = '#',
  label = '',
}) => (
  <>
    <a
      href={link}
      className={`${styles.lineButton} p-2 pl-4 pr-4 flex items-center justify-center bg-accent rounded-full`}
    >
      <img
        src="/images/icon-line.png"
        width="50px"
        height="50px"
        alt={label}
      ></img>
      <span className="text-white font-bold">{label}</span>
    </a>
  </>
)

/** Container Component */
const LineButtonContainer: React.FC<
  ContainerProps<LineButtonProps, PresenterProps>
> = ({ presenter, ...props }) => {
  return presenter({
    ...props,
  })
}

export default connect<LineButtonProps, PresenterProps>(
  'LineButton',
  LineButtonPresenter,
  LineButtonContainer
)
