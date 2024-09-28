import React, { FC } from 'react'
import { ContainerProps } from 'types'
import * as styles from './styles'
import { connect } from '@/components/hoc'
import { useI18n } from '@/components/i18n'
import { MenuItem } from '@/constants/menu'

/** SideMenuProps Props */
export type SideMenuProps = {
  setMenuOpen: () => void
  isMenuOpen: boolean
  menuItems: MenuItem[]
  position: 'right' | 'left'
}
/** Presenter Props */
export type PresenterProps = SideMenuProps & {
  setMenuOpen
  isMenuOpen
}

/** Presenter Component */
const SideMenuPresenter: FC<PresenterProps> = ({
  t,
  setMenuOpen,
  isMenuOpen,
  menuItems = [],
  position = 'right',
  ...props
}) => (
  <>
    <div
      className={`${styles.sideMenu} p-4 border-t z-50 h-screen bg-base ${
        isMenuOpen ? styles.menuOpen : styles.menuClose
      }`}
    >
      <ul className="list-none">
        {menuItems.map(({ label, href, target }, idx) => (
          <li className="my-6" key={idx}>
            <a
              href={href}
              target={target}
              rel={target ? 'noreferrer' : ''}
              className="break-words whitespace-pre-wrap text-gray-700 font-bold whitespace-nowrap"
            >
              {t(label)}
            </a>
          </li>
        ))}
      </ul>
    </div>
    <div
      className={`${
        isMenuOpen ? 'block' : 'hidden'
      } z-20 opacity-50 fixed top-0 w-full h-screen`}
      onClick={() => setMenuOpen(!isMenuOpen)}
    ></div>
  </>
)

/** Container Component */
const SideMenuContainer: React.FC<
  ContainerProps<SideMenuProps, PresenterProps>
> = ({ presenter, setMenuOpen, isMenuOpen, ...props }) => {
  const { t } = useI18n('Common')

  return presenter({
    t,
    setMenuOpen,
    isMenuOpen,
    ...props,
  })
}

export default connect<SideMenuProps, PresenterProps>(
  'SideMenu',
  SideMenuPresenter,
  SideMenuContainer
)
