import { useEffect, useState, ChangeEvent } from 'react';
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
  }, [props.identity]);

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
        readOnly={props.value !== undefined && !props.onChange}
        rows={props.rows || 4}
        cols={props.cols || 50}
      />
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

export default TextArea;
