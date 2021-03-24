import React from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { Form } from 'react-bootstrap'

const RequestToken = props => <Form.Control type="hidden" name="token" defaultValue={props.params.id} />
RequestToken.propTypes = {
  params: PropTypes.object,
}
const mapStateToProps = state => ({
  params: state.params,
})
const mapDispatchToProps = () => ({})
export default connect(mapStateToProps, mapDispatchToProps)(RequestToken)
