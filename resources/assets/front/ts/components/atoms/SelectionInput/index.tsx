import { useEffect, useState } from "react";
import styles from './styles.module.scss';

type Option = {
    label: string;
    value: string;
};

type Props = {
    identity: string;
    label?: string;
    controlType: 'checkbox' | 'radio';
    options: Option[];
    name?: string;
    checkedValues?: string[]; // For checkboxes
    selectedValue?: string;   // For radio
    required?: boolean;
    error?: string;
    className?: string;
    onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
};

type Valid = {
    isInvalid: string;
    error: string;
};

const SelectionInput = (props: Props) => {
    const [valid, setValid] = useState<Valid>({ error: "", isInvalid: "" });

    useEffect(() => {
        if (props.error) {
            setValid({ error: props.error, isInvalid: " is-invalid" });
        } else if (window.laravelErrors && window.laravelErrors[props.identity]) {
            setValid({
                error: window.laravelErrors[props.identity][0],
                isInvalid: " is-invalid",
            });
            delete window.laravelErrors[props.identity];
        } else {
            setValid({ error: "", isInvalid: "" });
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
            <div className="space-y-2">
                {props.options.map((option, index) => {
                    const id = `${props.identity}_${index}`;
                    const isChecked = props.controlType === 'checkbox'
                        ? props.checkedValues?.includes(option.value)
                        : props.selectedValue === option.value;
                    return (
                        <label key={option.value} htmlFor={id} className="flex items-center gap-2">
                            <input
                                type={props.controlType}
                                id={id}
                                name={props.name || props.identity}
                                value={option.value}
                                checked={isChecked}
                                onChange={props.onChange}
                                className={`${styles.formControl} ${valid.isInvalid}`}
                                required={props.required && index === 0} // ラジオ or 最初のチェックボックスのみ required
                            />
                            {option.label}
                        </label>
                    );
                })}
            </div>
            {valid.error && (
                <p className={styles.error}>
                    <strong>{valid.error}</strong>
                </p>
            )}
        </div>
    );
};

export default SelectionInput;
