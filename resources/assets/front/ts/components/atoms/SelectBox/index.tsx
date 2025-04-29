import {useEffect, useState, ChangeEvent} from 'react';
import styles from './styles.module.scss';

type Option = {
    label: string;
    value: string;
};

type Props = {
    identity: string;
    label: string;
    options: Option[];
    name?: string;
    selectedValue?: string;
    required?: boolean;
    error?: string;
    className?: string;
    onChange?: (e: ChangeEvent<HTMLSelectElement>) => void;
};

type Valid = {
    isInvalid: string;
    error: string;
};

const SelectBox = ({
      identity,
      label,
      options,
      name,
      selectedValue,
      required,
      error,
      className,
      onChange,
  }: Props) => {
    const [valid, setValid] = useState<Valid>({ error: '', isInvalid: '' });

    useEffect(() => {
        if (error) {
            setValid({ error, isInvalid: ' is-invalid' });
        } else if (window.laravelErrors && window.laravelErrors[identity]) {
            setValid({
                error: window.laravelErrors[identity][0],
                isInvalid: ' is-invalid',
            });
            delete window.laravelErrors[identity];
        } else {
            setValid({ error: '', isInvalid: '' });
        }
    }, [error, identity]);

    return (
        <div className={className}>
            <label className="font-bold" htmlFor={identity}>
                {label}
                {required && (
                    <span className="ml-2 text-red-600 text-sm font-normal">必須</span>
                )}
            </label>
            <select
                id={identity}
                name={name || identity}
                value={selectedValue}
                onChange={onChange}
                required={required}
                className={`${styles.formControl} ${valid.isInvalid}`}
            >
                <option value="">選択してください</option>
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
            {valid.error && (
                <p className={styles.error}>
                    <strong>{valid.error}</strong>
                </p>
            )}
        </div>
    );
};

export default SelectBox;
