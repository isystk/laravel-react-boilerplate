import React from 'react';
import { CommonHeader } from '../Header';
import { shallow } from 'enzyme'
import toJson from 'enzyme-to-json'
import * as hooks from '../Header.hooks'
import sinon from 'sinon'

describe('Header', () => {

  const stub = sinon.stub(hooks, 'useHeader');

  it('Header-Logout', () => {

    stub.returns({
      auths: {
        auth: false
      },
    });

    const wrapper = shallow(<CommonHeader/>);
    const tree = toJson(wrapper);

    expect(tree).toMatchSnapshot();
  });


  it('Header-Login', () => {

    stub.returns({
      auths: {
        auth: true,
        name: 'テスト'
      },
    })

    const wrapper = shallow(<CommonHeader/>);
    const tree = toJson(wrapper);

    expect(tree).toMatchSnapshot();
  });

})
