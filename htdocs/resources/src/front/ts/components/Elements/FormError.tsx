import React, { FC } from "react";

type Props = {
    message: string;
};

const FormError: FC<Props> = ({ message }) => (
    <span className="invalid-feedback" role="alert">
        <strong>{message}</strong>
    </span>
);
export default FormError;
