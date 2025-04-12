import React, { FC } from "react";
import { Row, Col, Button } from "reactstrap";

type Props = {
    label: string;
};

const SubmitButton: FC<Props> = (props) => (
    <Row className="form-group mb-0">
        <Col md="6" className="offset-md-4">
            <Button type="submit" color="primary">
                {props.label}
            </Button>
        </Col>
    </Row>
);
export default SubmitButton;
