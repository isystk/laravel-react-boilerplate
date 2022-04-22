import React from 'react';
import renderer from 'react-test-renderer';
import { Logo } from '../../../components/Commons/Logo';


test('Logo', () => {
  const component = renderer.create(
      <Logo />
  )
  expect(component).toMatchSnapshot()
})
