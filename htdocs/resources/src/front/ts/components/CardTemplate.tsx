import React from 'react'
import { Container, Row, Col, Card } from 'react-bootstrap'
import ContentSelector from './ContentSelector'

type Props = {
  title: string
  content: string
  params?: any
}

class CardTemplate extends React.Component<Props> {
  constructor(props) {
    super(props)
    if (props.params !== undefined) {
      props.setPrams(props.params)
    }
  }
  render() {
    return (
      <Container>
        <Row className="justify-content-center">
          <Col md="8">
            <Card>
              <Card.Header>{this.props.title}</Card.Header>
              <Card.Body>
                <ContentSelector content={this.props.content} />
              </Card.Body>
            </Card>
          </Col>
        </Row>
      </Container>
    )
  }
}

export default CardTemplate
