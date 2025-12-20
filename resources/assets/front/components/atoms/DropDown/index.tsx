import { useState, useEffect, useRef } from 'react';
import styles from './styles.module.scss';

type Props = {
  text: string;
  items: Array<{ text: string; onClick: () => void }>;
  className?: string;
};

const DropDown = ({ text, items, className }: Props) => {
  const [isOpen, setOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setOpen(false);
      }
    };

    document.addEventListener('click', handleClickOutside);
    return () => {
      document.removeEventListener('click', handleClickOutside);
    };
  }, []);

  return (
    <div ref={dropdownRef} className={`${styles.dropdown} ${className}`}>
      <button
        className={`btn ${styles.dropdownToggle}`}
        onClick={() => {
          setOpen(!isOpen);
        }}
      >
        {text}
        <svg
          className="-mr-1 size-5 text-gray-400"
          viewBox="0 0 20 20"
          fill="currentColor"
          aria-hidden="true"
          data-slot="icon"
        >
          <path
            fillRule="evenodd"
            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
            clipRule="evenodd"
          />
        </svg>
      </button>
      <div className={`dropdownMenu ${styles.dropdownMenu} ${isOpen ? '' : 'hidden'}`}>
        {items.map(({ text, onClick }, index) => (
          <button
            key={index}
            type="button"
            className={styles.dropdownItem}
            onClick={() => {
              onClick?.();
              setOpen(false);
            }}
          >
            {text}
          </button>
        ))}
      </div>
    </div>
  );
};

export default DropDown;
