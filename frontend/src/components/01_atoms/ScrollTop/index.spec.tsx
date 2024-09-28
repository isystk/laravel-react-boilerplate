import React from 'react'
import renderer from 'react-test-renderer'
import ScrollTop, { ScrollTopProps } from './index'

describe('ScrollTop', () => {
  it('Match Snapshot', () => {
    const props: ScrollTopProps = {
      scrollY: 0,
    }
    const component = renderer.create(<ScrollTop {...props} />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
