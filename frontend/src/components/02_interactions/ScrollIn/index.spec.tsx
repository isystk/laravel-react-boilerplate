import React from 'react'
import renderer from 'react-test-renderer'
import ScrollIn, { ScrollInProps } from './index'

describe('ScrollIn', () => {
  it('Match Snapshot', () => {
    const props: ScrollInProps = {
      delay: '1s',
    }
    const component = renderer.create(
      <ScrollIn {...props}>
        <div>Hello!</div>
      </ScrollIn>
    )
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
