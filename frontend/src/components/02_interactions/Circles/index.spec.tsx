import React from 'react'
import renderer from 'react-test-renderer'
import Circles from './index'

describe('Circles', () => {
  it('Match Snapshot', () => {
    const component = renderer.create(
      <Circles>
        <div
          style={{
            height: '600px',
          }}
        ></div>
      </Circles>
    )
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
