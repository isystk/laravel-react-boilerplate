import React, { FC, useEffect, useState } from 'react'
import { ContainerProps } from 'types'
import { connect } from '@/components/hoc'
import Logo from '@/components/01_atoms/Logo'
import Hamburger from '@/components/01_atoms/Hamburger'
import * as styles from './styles'
import { useI18n } from '@/components/i18n'
import { MenuItem } from '@/constants/menu'

/** HeaderProps Props */
export type HeaderProps = {
  isMenuOpen: boolean
  setMenuOpen: () => void
  menuItems: MenuItem[]
  scrollY: number
}
/** Presenter Props */
export type PresenterProps = HeaderProps & {
  t
}

/** Presenter Component */
const HeaderPresenter: FC<PresenterProps> = ({
  isMenuOpen,
  setMenuOpen,
  menuItems = [],
  t,
  isVisible,
}) => (
  <>
    <header
      className={`${styles.header} ${
        isVisible ? 'fixed' : 'bg-main'
      } z-50 flex justify-between items-center h-16 px-4 py-4 sm:px-8 w-full`}
    >
      <Logo />
      <div
        className={`flex pr-3 z-50 md:hidden ${
          isMenuOpen ? 'fixed right-5' : ''
        }`}
      >
        <Hamburger isMenuOpen={isMenuOpen} setMenuOpen={setMenuOpen} />
      </div>
      <nav className="hidden sm:block">
        <ul className="flex gap-6">
          {menuItems.map(({ label, href, target }, idx) => (
            <li key={idx}>
              <a href={href} target={target} rel={target ? 'noreferrer' : ''}>
                {t(label)}
              </a>
            </li>
          ))}
        </ul>
      </nav>
    </header>
  </>
)

/** Container Component */
const HeaderContainer: React.FC<
  ContainerProps<HeaderProps, PresenterProps>
> = ({ presenter, scrollY = 500, ...props }) => {
  const { t } = useI18n('Common')
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

  return presenter({
    t,
    isVisible,
    ...props,
  })
}

export default connect<HeaderProps, PresenterProps>(
  'Header',
  HeaderPresenter,
  HeaderContainer
)
