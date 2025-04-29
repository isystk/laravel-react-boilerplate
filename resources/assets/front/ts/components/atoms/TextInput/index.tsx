import {useEffect, useState, ChangeEvent} from "react";
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
    onChange?: (e: ChangeEvent<HTMLInputElement>) => void;
    onBlur?: (e: ChangeEvent<HTMLInputElement>) => void;
    value?: string;
    className?: string;
    error?: string;
};

type Valid = {
    isInvalid: string;
    error: string;
};

const TextInput = (props: Props) => {
    const [valid, setValid] = useState<Valid>({ error: "", isInvalid: "" });

    useEffect(() => {
        if (props.error) {
            setValid({
                error: props.error,
                isInvalid: " is-invalid",
            });
        } else if (window.laravelErrors && window.laravelErrors[props.identity]) {
            setValid({
                error: window.laravelErrors[props.identity][0],
                isInvalid: " is-invalid",
            });
            delete window.laravelErrors[props.identity];
        } else {
            setValid({
                error: "",
                isInvalid: "",
            });
        }
    }, [props.error, props.identity]);

    return (
        <div className={props.className}>
            <label className="font-bold" htmlFor={props.identity}>
                {props.label}
                {props.required && (
                    <span className="ml-2 text-red-600 text-sm font-normal">必須</span>
                )}
            </label>
            <input
                id={props.identity}
                name={props.name || props.identity}
                className={`${styles.formControl} ${valid.isInvalid}`}
                type={props.controlType}
                autoComplete={props.autoComplete}
                autoFocus={props.autoFocus}
                required={props.required}
                onChange={props.onChange}
                onBlur={props.onBlur}
                value={props.value}
                defaultValue={props.defaultValue}
            />
            {valid.error && (
                <p className={styles.error}>
                    <strong>{valid.error}</strong>
                </p>
            )}
        </div>
    );
};

export default TextInput;
