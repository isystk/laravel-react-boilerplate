import React from 'react'
import { Row, Col, Button } from 'react-bootstrap'
import { Link } from 'react-router-dom'

const LoginButton = () => (
    <Row className="form-group mb-0">
        <Col md="8" className="offset-md-4">
            <Button type="submit" variant="primary">
                Login
            </Button>
            <Link to="/password/reset" className="btn btn-link">
                Forgot Your Password?
            </Link>
        </Col>
    </Row>
)
export default LoginButton
