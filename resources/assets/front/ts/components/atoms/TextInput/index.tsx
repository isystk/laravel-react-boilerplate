import React, { useEffect, useState, FC } from "react";
import styles from './styles.module.scss';

type Props = {
    identity: string;
    controlType: string;
    name?: string;
    autoComplete?: string;
    label: string;
    defaultValue?: string;
    action?: any;
    autoFocus?: boolean;
    required?: boolean;
    onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
    value?: string;
    className?: string;
};

type Valid = {
    isInvalid: string;
    error: string;
};

declare global {
    interface Window {
        laravelErrors: {
            [key: string]: string[];
        };
    }
}

const TextInput: FC<Props> = (props) => {
    const [valid, setValid] = useState<Valid>({ error: "", isInvalid: "" });

    useEffect(() => {
        if (window.laravelErrors && window.laravelErrors[props.identity]) {
            setValid({
                error: window.laravelErrors[props.identity][0],
                isInvalid: " is-invalid",
            });
            delete window.laravelErrors[props.identity];
        }
    }, [props.identity]);

    return (
        <div className={props.className}>
            <label className="font-bold" htmlFor={props.identity}>{props.label}</label>
            <input
                id={props.identity}
                name={props.name || props.identity}
                className={`${styles.formControl} ${valid.isInvalid}`}
                type={props.controlType}
                autoComplete={props.autoComplete}
                autoFocus={props.autoFocus}
                required={props.required}
                onChange={props.onChange}
                value={props.value}
                defaultValue={props.defaultValue}
            />
            {valid.error && (
                <span className={styles.error}>
                    <strong>{valid.error}</strong>
                </span>
            )}
        </div>
    );
};

export default TextInput;
