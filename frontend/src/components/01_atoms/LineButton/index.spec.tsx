import React from 'react'
import renderer from 'react-test-renderer'
import LineButton, { LineButtonProps } from './index'

describe('LineButton', () => {
  it('Match Snapshot', () => {
    const props: LineButtonProps = {
      link: '#',
      label: '友達に追加して質問してみる',
    }
    const component = renderer.create(<LineButton {...props} />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
