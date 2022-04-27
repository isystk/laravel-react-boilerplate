import React, { FC } from "react";
import { Container, Row, Col, Card, CardTitle, CardText } from "reactstrap";

type Props = {
    title: string;
    children: React.ReactNode;
};

const Box: FC<Props> = ({ title, children }) => {
    return (
        <>
            <Container>
                <Row className="justify-content-center">
                    <Col md="8">
                        <Card>
                            <CardTitle className="card-header">
                                {title}
                            </CardTitle>
                            <CardText className="card-body">
                                {children}
                            </CardText>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    );
};

export default Box;
