import React, { FC, useState } from 'react'
import { ContainerProps } from 'types'
import { connect } from '@/components/hoc'
import * as styles from './styles'
import { useRouter } from 'next/router'

export type DropDownItem = {
  label: string
  link: string | (() => void)
}

/** DropDownProps Props */
export type DropDownProps = {
  label: string
  items: DropDownItem[]
}
/** Presenter Props */
export type PresenterProps = DropDownProps & {
  router
  showMenu
  setShowMenu
}

/** Presenter Component */
const DropDownPresenter: FC<PresenterProps> = ({
  label,
  items = [],
  router,
  showMenu,
  setShowMenu,
}) => (
  <>
    <div className="relative">
      <button
        id="dropdownNavbarLink"
        data-dropdown-toggle="dropdownNavbar"
        className={`${styles.dropDown} flex items-center justify-between md:py-2 pl-3 md:pr-4 rounded md:border-0 md:p-0 `}
        onClick={() => setShowMenu(!showMenu)}
      >
        <span className="w-32 md:w-48 md:pr-4 truncate">{label}</span>
        <svg
          className="w-5 h-5 ml-1"
          aria-hidden="true"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fillRule="evenodd"
            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
            clipRule="evenodd"
          ></path>
        </svg>
      </button>
      <div
        id="dropdownNavbar"
        className={`${
          showMenu ? '' : 'hidden'
        } z-10 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow absolute right-0 w-48`}
      >
        <ul
          className="py-2 text-sm text-gray-700 dark:text-gray-400"
          aria-labelledby="dropdownLargeButton"
        >
          {items.map((item, idx) => (
            <li key={idx}>
              <a
                href="#"
                className="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                onClick={
                  typeof item.link === 'string'
                    ? (e) => {
                        e.preventDefault()
                        router.push(item.link)
                      }
                    : item.link
                }
              >
                {item.label}
              </a>
            </li>
          ))}
        </ul>
      </div>
    </div>
  </>
)

/** Container Component */
const DropDownContainer: React.FC<
  ContainerProps<DropDownProps, PresenterProps>
> = ({ presenter, ...props }) => {
  const router = useRouter()
  const [showMenu, setShowMenu] = useState(false)
  return presenter({
    router,
    showMenu,
    setShowMenu,
    ...props,
  })
}

export default connect<DropDownProps, PresenterProps>(
  'DropDown',
  DropDownPresenter,
  DropDownContainer
)
