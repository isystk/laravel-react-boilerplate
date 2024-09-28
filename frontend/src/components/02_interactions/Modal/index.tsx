import React, { FC, useEffect, useRef, useState } from 'react'
import ReactDOM from 'react-dom'
import { ContainerProps, WithChildren } from 'types'
import * as styles from './styles'
import { connect } from '@/components/hoc'

/** ModalProps Props */
export type ModalProps = WithChildren & {
  isOpen: boolean
  acceptLabel?: string
  cancelLabel?: string
  handleAccept?: () => void
  handleCancel?: () => void
}
/** Presenter Props */
export type PresenterProps = ModalProps & { onClose; Portal }

/** Presenter Component */
const ModalPresenter: FC<PresenterProps> = ({
  children,
  isOpen,
  acceptLabel = 'はい',
  cancelLabel = 'いいえ',
  handleAccept = () => ({}),
  handleCancel = () => ({}),
  Portal,
  ...props
}) => (
  <>
    <Portal>
      {isOpen && (
        <>
          <div className="bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40"></div>
          <div
            id="popup-modal"
            className={`${
              isOpen ? '' : 'hidden '
            } fixed top-0 left-0 right-0 z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full`}
          >
            <div className="relative w-full max-w-md max-h-full m-auto">
              <div className="relative bg-base rounded-lg shadow dark:bg-gray-700">
                <button
                  type="button"
                  className="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                  data-modal-hide="popup-modal"
                  onClick={() => handleCancel()}
                >
                  <svg
                    aria-hidden="true"
                    className="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fillRule="evenodd"
                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                      clipRule="evenodd"
                    ></path>
                  </svg>
                  <span className="sr-only">Close modal</span>
                </button>
                <div className="p-6 text-center">
                  {children}
                  <button
                    data-modal-hide="popup-modal"
                    type="button"
                    className="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2"
                    onClick={() => handleAccept()}
                  >
                    {acceptLabel}
                  </button>
                  <button
                    data-modal-hide="popup-modal"
                    type="button"
                    className="text-gray-500 bg-base hover:bg-main focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                    onClick={() => handleCancel()}
                  >
                    {cancelLabel}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </>
      )}
    </Portal>
  </>
)

/** Container Component */
const ModalContainer: React.FC<ContainerProps<ModalProps, PresenterProps>> = ({
  presenter,
  children,
  ...props
}) => {
  const Portal: FC = ({ children }) => {
    const ref = useRef<HTMLElement>()
    const [mounted, setMounted] = useState(false)

    useEffect(() => {
      const current = document.querySelector<HTMLElement>('body')
      if (current) {
        ref.current = current
      }
      setMounted(true)
    }, [])

    return mounted
      ? ReactDOM.createPortal(
          <>{children}</>,
          ref.current ? ref.current : new Element()
        )
      : null
  }
  return presenter({ children, Portal, ...props })
}

export default connect<ModalProps, PresenterProps>(
  'Modal',
  ModalPresenter,
  ModalContainer
)
