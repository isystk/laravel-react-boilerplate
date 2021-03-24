import React from "react";

interface IProps {
  message: string;
}

const FormError = (props: IProps) => (
  <span className="invalid-feedback" role="alert">
    <strong>{props.message}</strong>
  </span>
);
export default FormError;
