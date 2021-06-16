import React from 'react';
import renderer from 'react-test-renderer';
import { CommonFooter } from '../Footer';

test('Footer', () => {
  const component = renderer.create(<CommonFooter />);
  const tree = component.toJSON();

  expect(tree).toMatchSnapshot();
});
