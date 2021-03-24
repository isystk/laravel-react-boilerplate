import React from 'react'
import { Row, Col, Button } from 'react-bootstrap'

interface IProps {
  label: string
}

const SubmitButton = (props: IProps) => (
  <Row className="form-group mb-0">
    <Col md="6" className="offset-md-4">
      <Button type="submit" variant="primary">
        {props.label}
      </Button>
    </Col>
  </Row>
)
export default SubmitButton
