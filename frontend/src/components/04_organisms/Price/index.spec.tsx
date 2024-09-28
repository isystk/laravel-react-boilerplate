import React from 'react'
import renderer from 'react-test-renderer'
import Price from './index'

describe('Price', () => {
  it('Match Snapshot', () => {
    const component = renderer.create(<Price />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
