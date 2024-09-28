import React from 'react'
import renderer from 'react-test-renderer'
import News from './index'

describe('News', () => {
  it('Match Snapshot', () => {
    const component = renderer.create(<News />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
