import React from 'react';
import { MemoryRouter } from 'react-router'
import {storiesOf} from '@storybook/react';
import MainService from "@/services/main";
import Header from '@/components/commons/Header';

storiesOf('commons/Header', module)
  .addDecorator(getStory => <MemoryRouter>{getStory()}</MemoryRouter>)
  .add('Logout', () => {
      const appRoot = {
          auth: {
              name: "",
          }
      } as MainService
      return <Header appRoot={appRoot}/>
  })
  .add('Logined', () => {
      const appRoot = {
          auth: {
              name: "sample",
              isLogined: true
          }
      } as MainService
      return <Header appRoot={appRoot}/>
  });
