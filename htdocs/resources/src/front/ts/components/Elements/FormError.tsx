import React from 'react'

type Props = {
  message: string
}

const FormError = (props: Props) => (
  <span className="invalid-feedback" role="alert">
    <strong>{props.message}</strong>
  </span>
)
export default FormError
