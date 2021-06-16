import React from 'react'
import { Row, Col, Form } from 'react-bootstrap'
import FormError from './FormError'

type Props = {
  identity: string
  controlType: string
  name?: string
  autoComplete?: string
  label: string
  defaultValue?: string
  action?: any
  autoFocus?: boolean
  required?: string
}

type State = {
  isInvalid: string
  error: string
}

class TextInput extends React.Component<Props, State> {
  constructor(props) {
    super(props)
    const valid = {
      error: '',
      isInvalid: '',
    }
    if (window.laravelErrors[this.props.identity] !== undefined) {
      valid.error = window.laravelErrors[this.props.identity][0]
      valid.isInvalid = ' is-invalid'
    }
    this.state = valid
  }
  render() {
    interface IInput {
      id?: string
      type?: string
      className?: string
      name?: string
      autoComplete?: string
      required?: any
      defaultValue?: string | undefined
      autoFocus?: boolean | undefined
      onChange?
    }
    const inputProps: IInput = {}
    inputProps.id = this.props.identity
    inputProps.type = this.props.controlType === undefined ? 'text' : this.props.controlType
    inputProps.className = this.state.isInvalid
    inputProps.name = this.props.name === undefined ? this.props.identity : this.props.name
    inputProps.autoComplete = this.props.autoComplete === undefined ? this.props.identity : this.props.autoComplete
    inputProps.required = this.props.required === undefined ? true : this.props.required
    inputProps.autoFocus = this.props.autoFocus === undefined ? false : this.props.autoFocus
    if (this.props.controlType !== 'password') {
      inputProps.defaultValue = this.props.defaultValue
      inputProps.onChange = text => {
        this.props.action(text.target.value)
      }
    }
    return (
      <Form.Group>
        <Row>
          <Form.Label htmlFor={this.props.identity} className="col-md-4 col-form-label text-md-right">
            {this.props.label}
          </Form.Label>
          <Col md="6">
            <Form.Control {...inputProps} />
            <FormError message={this.state.error} />
          </Col>
        </Row>
      </Form.Group>
    )
  }
}

export default TextInput
