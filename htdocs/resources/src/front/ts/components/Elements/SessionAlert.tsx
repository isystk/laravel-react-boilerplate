import React from 'react'
import { Alert } from 'react-bootstrap'

const SessionAlert = props => {
  if (window.laravelSession[props.target] !== '') {
    return (
      <Alert variant="success" role="alert">
        {props.target === 'resent'
          ? 'A fresh verification link has been sent to your email address.'
          : window.laravelSession[props.target]}
      </Alert>
    )
  } else {
    return <></>
  }
}

export default SessionAlert
