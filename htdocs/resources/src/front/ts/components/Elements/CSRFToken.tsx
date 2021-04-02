import React from 'react'
import { Form } from 'react-bootstrap'

type Props = {
  csrf: string
}

const CSRFToken = (props: Props) => <Form.Control type="hidden" name="_token" defaultValue={props.csrf} />

export default CSRFToken
