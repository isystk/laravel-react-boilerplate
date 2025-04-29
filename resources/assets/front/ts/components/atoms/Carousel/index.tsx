import { useState, useEffect, useRef } from "react";
import styles from './styles.module.scss';

type Props = {
    images: Array<{ src: string, alt?: string }>,
    className?: string,
    autoPlay?: boolean,
    autoPlayInterval?: number
};

const Carousel = ({ images, className, autoPlay = false, autoPlayInterval = 3000 }: Props) => {

    const [currentSlide, setCurrentSlide] = useState(0);
    const timerRef = useRef<number|null>(null);

    const nextSlide = () => {
        setCurrentSlide((prev) => (prev + 1) % images.length);
        resetAutoPlayTimer();
    };

    const prevSlide = () => {
        setCurrentSlide((prev) => (prev - 1 + images.length) % images.length);
        resetAutoPlayTimer();
    };

    const goToSlide = (index: number) => {
        setCurrentSlide(index);
        resetAutoPlayTimer();
    };

    const startAutoPlay = () => {
        if (autoPlay) {
            timerRef.current = setInterval(() => {
                setCurrentSlide((prev) => (prev + 1) % images.length);
            }, autoPlayInterval);
        }
    };

    const resetAutoPlayTimer = () => {
        if (!autoPlay) return;
        if (timerRef.current) {
            clearInterval(timerRef.current);
        }
        startAutoPlay();
    };

    useEffect(() => {
        startAutoPlay();

        return () => {
            if (timerRef.current) {
                clearInterval(timerRef.current);
            }
        };
    }, [autoPlay, autoPlayInterval, images.length]);

    return (
        <div className={`${styles.carouselContainer} ${className || ''}`}>
            {/* Slides */}
            <div
                className={styles.slides}
                style={{ transform: `translateX(-${currentSlide * 100}%)` }}
                id="carousel-slides"
            >
                {images.map((image, index) => (
                    <div className={styles.slide} key={index}>
                        <img
                            src={image.src}
                            alt={image.alt || ''}
                            className={styles.image}
                        />
                    </div>
                ))}
            </div>

            {/* Controls */}
            <button
                onClick={prevSlide}
                className={`${styles.control} ${styles.prev}`}
                aria-label="Previous Slide"
            >
                &#10094;
            </button>
            <button
                onClick={nextSlide}
                className={`${styles.control} ${styles.next}`}
                aria-label="Next Slide"
            >
                &#10095;
            </button>

            {/* Indicators */}
            <div className={styles.indicators} id="carousel-indicators">
                {images.map((_, index) => (
                    <button
                        key={index}
                        onClick={() => goToSlide(index)}
                        className={`${styles.indicator} ${index === currentSlide ? styles.active : ''}`}
                        aria-label={`Go to slide ${index + 1}`}
                    ></button>
                ))}
            </div>
        </div>
    );
};

export default Carousel;
