import React from 'react'
import renderer from 'react-test-renderer'
import Modal, { ModalProps } from './index'

describe('Modal', () => {
  it('Match Snapshot', () => {
    const props: ModalProps = {
      isOpen: true,
    }
    const component = renderer.create(<Modal {...props} />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
