import React from 'react'
import SessionAlert from '../Elements/SessionAlert'

const Verify = () => (
  <>
    <SessionAlert target="resent" />
    Before proceeding, please check your email for a verification link. If you did not receive the email,{' '}
    <a href="/email/resend">click here to request another</a>.
  </>
)

export default Verify
