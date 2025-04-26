import React, { FC } from "react";

type Props = {
    label: string;
};

const SubmitButton: FC<Props> = (props) => (
    <div className="form-group mb-0">
        <div className="offset-md-4">
            <button type="submit" color="primary">
                {props.label}
            </button>
        </div>
    </div>
);
export default SubmitButton;
