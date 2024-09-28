import React, { FC, useEffect, useState } from 'react'
import { ContainerProps } from 'types'
import { connect } from '@/components/hoc'
import * as styles from './styles'

/** ScrollTopProps Props */
export type ScrollTopProps = { scrollY }
/** Presenter Props */
export type PresenterProps = ScrollTopProps & {
  isVisible: boolean
  scrollToTop: () => void
}

/** Presenter Component */
const ScrollTopPresenter: FC<PresenterProps> = ({ isVisible, scrollToTop }) => (
  <>
    <button
      className={`${
        styles.scrollTop
      } fixed bottom-6 md:bottom-20 right-6 md:right-20 w-12 md:w-16 h-12 md:h-16 border-none bg-main rounded-full cursor-pointer transition-opacity duration-200 ease-in-out ${
        isVisible ? styles.showButton : styles.hideButton
      }`}
      onClick={scrollToTop}
    >
      <span className="text-bold text-gray-600">^</span>
    </button>
  </>
)

/** Container Component */
const ScrollTopContainer: React.FC<
  ContainerProps<ScrollTopProps, PresenterProps>
> = ({ presenter, scrollY = 500, ...props }) => {
  const [isVisible, setIsVisible] = useState(false)

  useEffect(() => {
    const toggleVisibility = () => {
      if (window.scrollY > scrollY) {
        setIsVisible(true)
      } else {
        setIsVisible(false)
      }
    }

    window.addEventListener('scroll', toggleVisibility)

    return () => window.removeEventListener('scroll', toggleVisibility)
  }, [scrollY])

  const scrollToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    })
  }

  return presenter({
    isVisible,
    scrollToTop,
    ...props,
  })
}

export default connect<ScrollTopProps, PresenterProps>(
  'ScrollTop',
  ScrollTopPresenter,
  ScrollTopContainer
)
