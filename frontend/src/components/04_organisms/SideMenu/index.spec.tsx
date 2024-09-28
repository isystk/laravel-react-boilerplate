import React from 'react'
import renderer from 'react-test-renderer'
import SideMenu, { SideMenuProps } from './index'

import { Context } from '@/components/05_layouts/HtmlSkeleton'

describe('SideMenu', () => {
  it('Match Snapshot', () => {
    const props: SideMenuProps = {
      isMenuOpen: true,
      setMenuOpen: () => ({}),
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

    const component = renderer.create(<SideMenu {...props} />)
    const tree = component.toJSON()

    expect(tree).toMatchSnapshot()
  })
})
