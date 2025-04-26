import React, { FC } from "react";

type Props = {
    identity: string;
    checked: boolean;
    label: string;
    action;
};

const CheckInput: FC<Props> = props => (
    <div>
        <div>
            <div className="offset-md-4">
                <div className="form-check">
                    <input
                        id={props.identity}
                        type="checkbox"
                        className="form-check-input"
                        name={props.identity}
                        checked={props.checked}
                        onChange={check => props.action(check.target.checked)}
                    />
                    <label
                        className="form-check-label"
                        htmlFor={props.identity}
                    >
                        {props.label}
                    </label>
                </div>
            </div>
        </div>
    </div>
);
export default CheckInput;
