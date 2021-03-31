import React from 'react';
import { CommonHeader } from '../Header';
import { shallow } from 'enzyme'
import toJson from 'enzyme-to-json'

describe('Header', () => {

  test('Header-Logout', () => {
    const component = shallow(<CommonHeader/>);
    const tree = toJson(component);

    expect(tree).toMatchSnapshot();
  });

  // test('Header-Login', () => {
  //   const component = renderer.create(<CommonHeader auth={{auth: true, name: 'テスト'}} push={()=>{}}/>);
  //   const tree = component.toJSON();

  //   expect(tree).toMatchSnapshot();
  // });

})
