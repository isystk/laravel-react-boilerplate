import React from 'react'
import renderer from 'react-test-renderer'
import Hamburger, { HamburgerProps } from './index'

describe('Hamburger', () => {
  it('Match Snapshot', () => {
    const props: HamburgerProps = {
      isMenuOpen: false,
      setMenuOpen: (isMenuOpen) => console.log(isMenuOpen),
    }
    const component = renderer.create(<Hamburger {...props} />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
