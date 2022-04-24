import React, { FC } from "react";
import { Container, Row, Col, Card } from "react-bootstrap";

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
                            <Card.Header>{title}</Card.Header>
                            <Card.Body>{children}</Card.Body>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    );
};

export default Box;
