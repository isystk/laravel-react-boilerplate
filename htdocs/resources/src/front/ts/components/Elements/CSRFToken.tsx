import React from 'react'
import { Form } from 'react-bootstrap'
import { connect } from 'react-redux'

interface IProps {
  csrf: string
}

const CSRFToken = (props: IProps) => <Form.Control type="hidden" name="_token" defaultValue={props.csrf} />

const mapStateToProps = state => {
  return {
    csrf: state.auth.csrf,
  }
}
const mapDispatchToProps = () => ({})
export default connect(mapStateToProps, mapDispatchToProps)(CSRFToken)
