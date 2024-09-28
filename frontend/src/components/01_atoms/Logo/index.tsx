import React, { FC } from 'react'
import { ContainerProps, WithChildren } from 'types'
import { connect } from '@/components/hoc'
import * as styles from './styles'
import { APP_NAME } from '@/constants'
import { Url } from '@/constants/url'
import Image, {
  PresenterProps as ImagePresenterProps,
} from '@/components/01_atoms/Image'
import { useRouter } from 'next/router'

/** LogoProps Props */
export type LogoProps = {
  name?: string
  link?: string
}
/** Presenter Props */
export type PresenterProps = LogoProps & {
  router
}

/** Presenter Component */
const LogoPresenter: FC<PresenterProps> = ({
  name = APP_NAME,
  link = Url.Top,
  router,
}) => {
  const props: ImagePresenterProps = {
    src: '/images/logo.png',
    alt: name,
    className: 'w-20 md:w-40',
    lazy: false,
  }
  return (
    <>
      <a
        href="#"
        className={styles.logo}
        onClick={(e) => {
          e.preventDefault()
          router.push(link)
        }}
      >
        <Image {...props} />
      </a>
    </>
  )
}

/** Container Component */
const LogoContainer: React.FC<ContainerProps<LogoProps, PresenterProps>> = ({
  presenter,
  ...props
}) => {
  const router = useRouter()
  return presenter({
    router,
    ...props,
  })
}

export default connect<LogoProps, PresenterProps>(
  'Logo',
  LogoPresenter,
  LogoContainer
)
