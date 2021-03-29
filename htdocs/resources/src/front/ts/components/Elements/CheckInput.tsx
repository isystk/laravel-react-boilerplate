import React from 'react'
import { Row, Col, Form } from 'react-bootstrap'

type Props = {
  identity: string
  checked: boolean
  label: string
  action
}

const CheckInput = (props: Props) => (
  <Form.Group>
    <Row>
      <Col md="6" className="offset-md-4">
        <div className="form-check">
          <Form.Check
            id={props.identity}
            type="checkbox"
            className="form-check-input"
            name={props.identity}
            checked={props.checked}
            onChange={check => props.action(check.target.checked)}
          />
          <Form.Label className="form-check-label" htmlFor={props.identity}>
            {props.label}
          </Form.Label>
        </div>
      </Col>
    </Row>
  </Form.Group>
)
export default CheckInput
