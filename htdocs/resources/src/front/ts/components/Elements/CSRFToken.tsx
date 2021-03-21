import React from 'react'
import { Form } from 'react-bootstrap'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'

const CSRFToken = (props) => (
    <Form.Control type="hidden" name="_token" defaultValue={props.csrf} />
)
CSRFToken.propTypes = {
    csrf: PropTypes.string,
}
const mapStateToProps = state => ({
    csrf: state.csrf,
})
const mapDispatchToProps = dispatch => ({
})
export default connect(mapStateToProps, mapDispatchToProps)(CSRFToken)
