import React from 'react'
import renderer from 'react-test-renderer'
import DotPulse from './index'

describe('DotPulse', () => {
  it('Match Snapshot', () => {
    const component = renderer.create(<DotPulse />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
