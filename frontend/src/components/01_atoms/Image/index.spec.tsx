import React from 'react'
import renderer from 'react-test-renderer'
import Image, { ImageProps } from './index'

describe('Image', () => {
  it('Match Snapshot', () => {
    const props: ImageProps = {
      src: '/images/dummy_480x320.png',
      alt: 'dummy',
      zoom: true,
    }
    const component = renderer.create(
      <div className="w-64 rounded-lg overflow-hidden">
        <Image {...props} />
      </div>
    )
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
    expect(true)
  })
})
