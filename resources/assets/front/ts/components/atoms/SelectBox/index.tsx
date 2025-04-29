import React, { useEffect, useState, FC } from 'react';
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
    onChange?: (e: React.ChangeEvent<HTMLSelectElement>) => void;
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

const SelectBox: FC<Props> = ({
      identity,
      label,
      options,
      name,
      selectedValue,
      required,
      error,
      className,
      onChange,
  }) => {
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
            <label htmlFor={identity} className="font-bold block mb-1">
                {label}
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
