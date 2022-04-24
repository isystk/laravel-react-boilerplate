import React, { useEffect, useState, FC } from "react";
import { Row, Col, Form } from "react-bootstrap";
import FormError from "./FormError";
import { FormControlProps } from "react-bootstrap/FormControl";

type Props = {
    identity: string;
    controlType: string;
    name?: string;
    autoComplete?: string;
    label: string;
    defaultValue?: string;
    action?: any;
    autoFocus?: boolean;
    required?: string;
};

type Valid = {
    isInvalid: string;
    error: string;
};

const TextInput: FC<Props> = props => {
    const [valid, setValid] = useState<Valid>({ error: "", isInvalid: "" });
    const formProps = { ...props } as FormControlProps;

    useEffect(() => {
        if (window.laravelErrors[props.identity]) {
            setValid({
                error: window.laravelErrors[props.identity][0],
                isInvalid: " is-invalid"
            });
            delete window.laravelErrors[props.identity];
        }
    }, []);

    return (
        <Form.Group>
            <Row>
                <Form.Label
                    htmlFor={props.identity}
                    className="col-md-4 col-form-label text-md-right"
                >
                    {props.label}
                </Form.Label>
                <Col md="6">
                    <Form.Control
                        {...formProps}
                        name={props.identity}
                        className={valid.isInvalid}
                    />
                    <FormError message={valid.error} />
                </Col>
            </Row>
        </Form.Group>
    );
};

export default TextInput;
