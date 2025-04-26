import React, { useEffect, useState, FC } from "react";
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
        <div>
            <div>
                <div className="text-md-right">
                    <label htmlFor={props.identity}>{props.label}</label>
                </div>
                <div >
                    <input
                        name={props.identity}
                        className={valid.isInvalid}
                    />
                    <FormError message={valid.error} />
                </div>
            </div>
        </div>
    );
};

export default TextInput;
