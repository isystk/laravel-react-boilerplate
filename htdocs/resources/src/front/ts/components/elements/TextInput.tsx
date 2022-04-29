import React, { useEffect, useState, FC } from "react";
import { Row, Col, Input, FormGroup, Label, InputProps } from "reactstrap";
import FormError from "./FormError";

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

const TextInput: FC<Props> = (props) => {
    const [valid, setValid] = useState<Valid>({ error: "", isInvalid: "" });
    const formProps = { ...props } as InputProps;

    useEffect(() => {
        if (window.laravelErrors[props.identity]) {
            setValid({
                error: window.laravelErrors[props.identity][0],
                isInvalid: " is-invalid",
            });
            delete window.laravelErrors[props.identity];
        }
    }, []);

    return (
        <FormGroup>
            <Row>
                <Col md="4" className="text-md-right">
                    <Label htmlFor={props.identity}>{props.label}</Label>
                </Col>
                <Col md="6">
                    <Input
                        {...formProps}
                        name={props.identity}
                        className={valid.isInvalid}
                    />
                    <FormError message={valid.error} />
                </Col>
            </Row>
        </FormGroup>
    );
};

export default TextInput;
