import { useEffect, useState, ChangeEvent } from 'react';
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

const SelectBox = (props: Props) => {
  const [laravelValid, setLaravelValid] = useState<Valid>({ error: '', isInvalid: '' });
  const [valid, setValid] = useState<Valid>({ error: '', isInvalid: '' });

  // 初回マウント時のみ Laravel のエラーを取り込み
  useEffect(() => {
    if (typeof window !== 'undefined' && window.laravelErrors?.[props.identity]) {
      const message = window.laravelErrors[props.identity][0];
      setLaravelValid({
        error: message,
        isInvalid: ' is-invalid',
      });
      delete window.laravelErrors[props.identity];
    }
  }, []);

  useEffect(() => {
    if (props.error) {
      setValid({
        error: props.error,
        isInvalid: ' is-invalid',
      });
    } else {
      setValid({
        error: '',
        isInvalid: '',
      });
    }
  }, [props.error]);

  return (
    <div className={props.className}>
      <label className="font-bold" htmlFor={props.identity}>
        {props.label}
        {props.required && <span className="ml-2 text-red-600 text-sm font-normal">必須</span>}
      </label>
      <select
        id={props.identity}
        name={props.name || props.identity}
        value={props.selectedValue}
        onChange={props.onChange}
        required={props.required}
        className={`${styles.formControl} ${valid.isInvalid}`}
      >
        <option value="">選択してください</option>
        {props.options.map(option => (
          <option key={option.value} value={option.value}>
            {option.label}
          </option>
        ))}
      </select>
      {laravelValid.error && (
        <p className={styles.error}>
          <strong>{laravelValid.error}</strong>
        </p>
      )}
      {valid.error && (
        <p className={styles.error}>
          <strong>{valid.error}</strong>
        </p>
      )}
    </div>
  );
};

export default SelectBox;
