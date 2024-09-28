import React from 'react'
import renderer from 'react-test-renderer'
import MainVisual from './index'

describe('MainVisual', () => {
  it('Match Snapshot', () => {
    const component = renderer.create(<MainVisual />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
