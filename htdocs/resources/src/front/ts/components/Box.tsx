import React, { FC } from "react";
import { Container, Row, Col, Card, CardHeader, CardBody } from "reactstrap";

type Props = {
    title: string;
    children: React.ReactNode;
    small?: boolean;
};

const Box: FC<Props> = ({ title, children, small }) => {
    const grids = {
        md: small ? 8 : 12
    };
    return (
        <>
            <Container>
                <Row className="justify-content-center">
                    <Col {...grids}>
                        <Card>
                            <CardHeader>{title}</CardHeader>
                            <CardBody>{children}</CardBody>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    );
};

export default Box;
