import React, { FC } from 'react'
import { ContainerProps } from 'types'
import { connect } from '@/components/hoc'
import * as styles from './styles'
import { useRouter } from 'next/router'
import { Url } from '@/constants/url'

export type BreadcrumbItem = {
  label: string
  link?: string
}

/** BreadcrumbProps Props */
export type BreadcrumbProps = {
  items: BreadcrumbItem[]
}
/** Presenter Props */
export type PresenterProps = BreadcrumbProps & {
  router
}

/** Presenter Component */
const BreadcrumbPresenter: FC<PresenterProps> = ({ items, router }) => (
  <>
    <nav
      className={`${styles.breadcrumb} flex mt-8 ml-8`}
      aria-label="Breadcrumb"
    >
      <ol className="inline-flex items-center space-x-1 md:space-x-3">
        <li className="inline-flex items-center">
          <a
            href="#"
            className="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
            onClick={(e) => {
              e.preventDefault()
              router.push(Url.AdminHome)
            }}
          >
            <svg
              aria-hidden="true"
              className="w-4 h-4 mr-2"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            Home
          </a>
        </li>
        {items.map((item, idx) => (
          <li key={idx}>
            <div className="flex items-center">
              <svg
                aria-hidden="true"
                className="w-6 h-6 text-gray-400"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fillRule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clipRule="evenodd"
                ></path>
              </svg>
              {item.link ? (
                <a
                  href="#"
                  className="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white"
                  onClick={(e) => {
                    e.preventDefault()
                    router.push(item.link)
                  }}
                >
                  {item.label}
                </a>
              ) : (
                <span className="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                  {item.label}
                </span>
              )}
            </div>
          </li>
        ))}
      </ol>
    </nav>
  </>
)

/** Container Component */
const BreadcrumbContainer: React.FC<
  ContainerProps<BreadcrumbProps, PresenterProps>
> = ({ presenter, ...props }) => {
  const router = useRouter()
  return presenter({
    router,
    ...props,
  })
}

export default connect<BreadcrumbProps, PresenterProps>(
  'Breadcrumb',
  BreadcrumbPresenter,
  BreadcrumbContainer
)
