import React from 'react'
import renderer from 'react-test-renderer'
import Footer, { FooterProps } from './index'

describe('Footer', () => {
  it('Match Snapshot', () => {
    const props: FooterProps = {
      menuItems: [
        {
          label: 'Menu1',
          href: 'https://menu1',
        },
        {
          label: 'Menu2',
          href: 'https://menu2',
        },
        {
          label: 'Menu3',
          href: 'https://menu3',
          target: '_blank',
        },
      ],
    }
    const component = renderer.create(<Footer {...props} />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
