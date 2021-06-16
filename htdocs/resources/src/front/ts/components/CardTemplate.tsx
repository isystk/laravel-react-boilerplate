import React, { useEffect } from 'react'
import { useDispatch } from 'react-redux'
import { Container, Row, Col, Card } from 'react-bootstrap'
import ContentSelector from './ContentSelector'
import { setPrams } from '../actions/auth'
import PropTypes from 'prop-types'

const CardTemplate = props => {
  const dispatch = useDispatch()

  useEffect(() => {
    if (props.params !== undefined) {
      dispatch(setPrams(props.params))
    }
  }, [])

  return (
    <>
      <Container>
        <Row className="justify-content-center">
          <Col md="8">
            <Card>
              <Card.Header>{props.title}</Card.Header>
              <Card.Body>
                <ContentSelector content={props.content} />
              </Card.Body>
            </Card>
          </Col>
        </Row>
      </Container>
    </>
  )
}

CardTemplate.propTypes = {
  title: PropTypes.string,
  content: PropTypes.string,
  params: PropTypes.object,
}

export default CardTemplate
