import React from "react";
import { connect } from "react-redux";
import { Container, Row, Col, Card } from "react-bootstrap";
import ContentSelector from "./ContentSelector";
import { setPrams } from "../actions/auth";

interface IProps {
  title: string;
  content: string;
  params?: any;
}

class CardTemplate extends React.Component<IProps> {
  constructor(props) {
    super(props);
    if (props.params !== undefined) {
      props.setPrams(props.params);
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
    );
  }
}

const mapStateToProps = () => ({});
const mapDispatchToProps = (dispatch) => ({
  setPrams: (request) => dispatch(setPrams(request)),
});
export default connect(mapStateToProps, mapDispatchToProps)(CardTemplate);
