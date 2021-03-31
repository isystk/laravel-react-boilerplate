import React from 'react'
import { Row, Col, Button } from 'react-bootstrap'
import { Link } from 'react-router-dom'

const LoginButton = () => (
  <Row className="form-group mt-3">
    <Col md="8" className="text-center">
      <Button type="submit" variant="primary">
        ログイン
      </Button>
      <Link to="/password/reset" className="btn btn-link">
        パスワードを忘れた方
      </Link>
    </Col>
  </Row>
)
export default LoginButton
