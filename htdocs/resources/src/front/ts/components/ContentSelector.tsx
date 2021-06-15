import React from 'react'
import LoginForm from '../containers/Forms/LoginForm'
import RegisterForm from '../containers/Forms/RegisterForm'
import EMailForm from '../containers/Forms/EMailForm'
import ResetForm from '../containers/Forms/ResetForm'
import Home from './Forms/Home'
import Verify from './Forms/Verify'

type Props = {
  content: string
}

const ContentSelector = (props: Props) => {
  switch (props.content) {
    case 'LoginForm':
      return <LoginForm />
    case 'RegisterForm':
      return <RegisterForm />
    case 'EMailForm':
      return <EMailForm />
    case 'ResetForm':
      return <ResetForm />
    case 'Home':
      return <Home />
    case 'Verify':
      return <Verify />
    default:
      return <div>UnMatch</div>
  }
}
export default ContentSelector
