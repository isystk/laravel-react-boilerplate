import React, { VFC } from "react";

type Props = {
    message: string;
};

const FormError: VFC<Props> = ({ message }) => (
    <span className="invalid-feedback" role="alert">
        <strong>{message}</strong>
    </span>
);
export default FormError;
