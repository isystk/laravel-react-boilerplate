import React, { FC } from "react";
import { Row, Col, FormGroup, Input, Label } from "reactstrap";

type Props = {
    identity: string;
    checked: boolean;
    label: string;
    action;
};

const CheckInput: FC<Props> = props => (
    <FormGroup>
        <Row>
            <Col md="6" className="offset-md-4">
                <div className="form-check">
                    <Input
                        id={props.identity}
                        type="checkbox"
                        className="form-check-input"
                        name={props.identity}
                        checked={props.checked}
                        onChange={check => props.action(check.target.checked)}
                    />
                    <Label
                        className="form-check-label"
                        htmlFor={props.identity}
                    >
                        {props.label}
                    </Label>
                </div>
            </Col>
        </Row>
    </FormGroup>
);
export default CheckInput;
