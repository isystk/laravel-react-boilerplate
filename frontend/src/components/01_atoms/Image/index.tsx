import React, { FC } from 'react'
import { ContainerProps } from 'types'
import { connect } from '@/components/hoc'
import * as styles from './styles'

/** ImageProps Props */
export type ImageProps = {
  src: string
  alt: string
  className?: string
  lazy?: boolean
  zoom?: boolean
}
/** Presenter Props */
export type PresenterProps = ImageProps

/** Presenter Component */
const ImagePresenter: FC<PresenterProps> = ({
  src,
  alt,
  className,
  lazy = true,
  zoom = false,
}) => (
  <div className={className}>
    <img
      src={src}
      alt={alt}
      className={`${styles.image} ${zoom ? 'zoom' : ''}`}
      loading={lazy ? 'lazy' : undefined}
    />
  </div>
)

/** Container Component */
const ImageContainer: React.FC<ContainerProps<ImageProps, PresenterProps>> = ({
  presenter,
  ...props
}) => {
  return presenter({
    ...props,
  })
}

export default connect<ImageProps, PresenterProps>(
  'Image',
  ImagePresenter,
  ImageContainer
)
