import React from 'react';
import renderer from 'react-test-renderer';
import { CommonHeader } from '../Header';

describe('Header', () => {

  test('Header-Logout', () => {
    const component = renderer.create(<CommonHeader auth={{auth: false}} push={()=>{}}/>);
    const tree = component.toJSON();

    expect(tree).toMatchSnapshot();
  });

  // test('Header-Login', () => {
  //   const component = renderer.create(<CommonHeader auth={{auth: true, name: 'テスト'}} push={()=>{}}/>);
  //   const tree = component.toJSON();

  //   expect(tree).toMatchSnapshot();
  // });

})
