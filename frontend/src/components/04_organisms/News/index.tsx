import React, { FC } from 'react'
import { ContainerProps } from 'types'
import * as styles from './styles'
import { connect } from '@/components/hoc'
import ScrollIn from '@/components/02_interactions/ScrollIn'
import Image from '@/components/01_atoms/Image'

/** NewsProps Props */
export type NewsProps = Record<never, never>
/** Presenter Props */
export type PresenterProps = NewsProps

/** Presenter Component */
const NewsPresenter: FC<PresenterProps> = ({ ...props }) => (
  <>
    <section className={`${styles.news}`}>
      <div className="flex items-center bg-base justify-center w-full">
        <div className="py-32 w-full">
          <ScrollIn>
            <p className="text-black font-bold text-3xl md:mb-16 text-center">
              NEWS
            </p>
          </ScrollIn>
          <div className="md:px-64">
            <ScrollIn>
              <div className="flex flex-wrap items-center p-8 md:mb-16">
                <div className="flex w-full md:w-1/2 justify-end items-center">
                  <div className="w-full">
                    <p className="text-accent font-bold text-xl mb-4">
                      2022.04.22
                    </p>
                    <p className="font-bold break-words mb-4">
                      XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
                    </p>
                  </div>
                </div>
                <div className="flex w-full md:w-1/2 justify-center md:justify-start items-center">
                  <div className="mx-4 md:mx-0 md:ml-16 rounded-lg overflow-hidden">
                    <Image
                      src="/images/dummy_480x320.png"
                      alt="dummy"
                      lazy={false}
                      zoom={true}
                    />
                  </div>
                </div>
              </div>
            </ScrollIn>
            <ScrollIn>
              <div className="bg-gray-300 flex-none h-px"></div>
              <div className="flex flex-wrap items-center p-8 md:mb-16">
                <div className="flex w-full md:w-1/2 justify-end items-center">
                  <div className="w-full">
                    <p className="text-accent font-bold text-xl mb-4">
                      2022.04.22
                    </p>
                    <p className="font-bold break-words mb-4">
                      XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
                    </p>
                  </div>
                </div>
                <div className="flex w-full md:w-1/2 justify-center md:justify-start items-center">
                  <div className="mx-4 md:mx-0 md:ml-16 rounded-lg overflow-hidden">
                    <Image
                      src="/images/dummy_480x320.png"
                      alt="dummy"
                      lazy={false}
                      zoom={true}
                    />
                  </div>
                </div>
              </div>
            </ScrollIn>
            <div className="mb-36"></div>
          </div>
        </div>
      </div>
    </section>
  </>
)

/** Container Component */
const NewsContainer: React.FC<ContainerProps<NewsProps, PresenterProps>> = ({
  presenter,
  ...props
}) => {
  return presenter({
    ...props,
  })
}

export default connect<NewsProps, PresenterProps>(
  'News',
  NewsPresenter,
  NewsContainer
)
