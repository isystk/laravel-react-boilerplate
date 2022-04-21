import React, { useEffect, VFC } from "react";
import { useDispatch } from "react-redux";
import { Container, Row, Col, Card } from "react-bootstrap";
import ContentSelector from "./ContentSelector";
import { setPrams } from "@/actions/auth";

type Props = {
    title: string;
    content: string;
    params?: { match: { params: any } };
};

const CardTemplate: VFC<Props> = ({ title, content, params }) => {
    const dispatch = useDispatch();

    useEffect(() => {
        if (params) {
            dispatch(setPrams(params));
        }
    }, []);

    return (
        <>
            <Container>
                <Row className="justify-content-center">
                    <Col md="8">
                        <Card>
                            <Card.Header>{title}</Card.Header>
                            <Card.Body>
                                <ContentSelector content={content} />
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    );
};

export default CardTemplate;
