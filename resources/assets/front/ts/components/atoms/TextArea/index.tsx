import {useEffect, useState, ChangeEvent} from "react";
import styles from './styles.module.scss';

type Props = {
    identity: string;
    name?: string;
    autoComplete?: string;
    label: string;
    defaultValue?: string;
    action?: any;
    autoFocus?: boolean;
    required?: boolean;
    onChange?: (e: ChangeEvent<HTMLTextAreaElement>) => void;
    onBlur?: (e: ChangeEvent<HTMLTextAreaElement>) => void;
    value?: string;
    className?: string;
    error?: string;
    rows?: number;
    cols?: number;
};

type Valid = {
    isInvalid: string;
    error: string;
};

const TextArea = (props: Props) => {
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
            <textarea
                id={props.identity}
                name={props.name || props.identity}
                className={`${styles.formControl} ${valid.isInvalid}`}
                autoComplete={props.autoComplete}
                autoFocus={props.autoFocus}
                required={props.required}
                onChange={props.onChange}
                onBlur={props.onBlur}
                value={props.value}
                defaultValue={props.defaultValue}
                rows={props.rows || 4}
                cols={props.cols || 50}
            />
            {valid.error && (
                <p className={styles.error}>
                    <strong>{valid.error}</strong>
                </p>
            )}
        </div>
    );
};

export default TextArea;
