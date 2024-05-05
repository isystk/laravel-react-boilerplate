import React from 'react';
import { Provider } from "react-redux";
import { MemoryRouter } from 'react-router'
import { storiesOf } from '@storybook/react';
import { Url } from "@/constants/url";
import reducers from "@/stores";
import thunk from "redux-thunk";
import { createStore, applyMiddleware } from "redux";
import { composeWithDevTools } from "redux-devtools-extension";
const enhancer =
    composeWithDevTools(applyMiddleware(thunk))
const store = createStore(reducers, enhancer);

import Logo from '@/components/commons/Logo';

storiesOf('commons/Logo', module)
    .addDecorator(story => <Provider store={store}>{story()}</Provider>)
    .addDecorator(story => (
        <MemoryRouter initialEntries={[Url.TOP]}>{story()}</MemoryRouter>
    ))
    .add('default', () => <Logo />);
