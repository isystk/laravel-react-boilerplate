import React from 'react'
import PropTypes from 'prop-types'
import { Form } from 'react-bootstrap'

const RequestToken = props => <Form.Control type="hidden" name="token" defaultValue={props.params.id} />
RequestToken.propTypes = {
  params: PropTypes.object,
}
export default RequestToken
