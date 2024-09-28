import React from 'react'
import renderer from 'react-test-renderer'
import Loading, { LoadingProps } from './index'

describe('Loading', () => {
  it('Match Snapshot', () => {
    const props: LoadingProps = {
      loading: true,
    }
    const component = renderer.create(<Loading {...props} />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
