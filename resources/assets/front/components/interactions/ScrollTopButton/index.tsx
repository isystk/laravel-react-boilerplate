import styles from './styles.module.scss';
import React, { useEffect, useState } from 'react';
import Portal from '@/components/interactions/Portal';

const ScrollTopButton: React.FC = () => {
  const [isVisible, setIsVisible] = useState(false);

  const handleScroll = () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    setIsVisible(scrollTop > 300); // 300px以上スクロールしたら表示
  };

  const scrollToTop = () => {
    const duration = 500;
    const start = window.scrollY;
    const startTime = performance.now();

    const animateScroll = (currentTime: number) => {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const ease = 1 - Math.pow(1 - progress, 3); // ease-out cubic
      window.scrollTo(0, start * (1 - ease));

      if (progress < 1) {
        requestAnimationFrame(animateScroll);
      }
    };

    requestAnimationFrame(animateScroll);
  };

  useEffect(() => {
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <Portal>
      <button
        onClick={scrollToTop}
        className={`${styles.button} ${isVisible ? styles.show : styles.hide}`}
      >
        <span className={styles.buttonLabel}>^</span>
      </button>
    </Portal>
  );
};

export default ScrollTopButton;
