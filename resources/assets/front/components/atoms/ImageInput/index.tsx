import { useEffect, useState, ChangeEvent } from 'react';
import styles from './styles.module.scss';
import Image from '@/components/atoms/Image';
import noImage from '@/assets/images/no_image.png';

type Props = {
  identity: string;
  name?: string;
  label: string;
  value?: string;
  autoFocus?: boolean;
  required?: boolean;
  className?: string;
  error?: string;
  setFieldValue?: (field: string, value: any) => void;
};

type Valid = {
  isInvalid: string;
  error: string;
};

const ImageInput = (props: Props) => {
  const [preview, setPreview] = useState<string | null>(null);
  const [, setBase64] = useState<string>('');
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

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onloadend = () => {
      const result = reader.result as string;
      setBase64(result);
      setPreview(result);

      // Formik に反映させる
      if (props.setFieldValue) {
        props.setFieldValue(props.name || props.identity, result);
      }
    };
    reader.readAsDataURL(file);
  };

  return (
    <div className={props.className}>
      <label className="font-bold" htmlFor={props.identity}>
        {props.label}
        {props.required && <span className="ml-2 text-red-600 text-sm font-normal">必須</span>}
      </label>
      <input
        id={props.identity}
        type="file"
        accept="image/*"
        className={`btn ${styles.formControl} ${valid.isInvalid}`}
        autoFocus={props.autoFocus}
        required={props.required}
        onChange={handleChange}
      />
      <input type="hidden" name={props.name || props.identity} value={props.value} />
      <div className={styles.previewContainer}>
        {preview ? (
          <Image src={preview} alt="プレビュー" className={styles.previewImage} />
        ) : (
          <Image src={noImage as string} alt="no image" className={styles.previewImage} />
        )}
      </div>
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

export default ImageInput;
