import React, {useState} from "react";
import styles from './styles.module.scss';

type Props = {
    images: Array<{ src: string, alt?: string }>,
    className?: string
};

const Carousel = ({images, className}: Props) => {

    const [currentSlide, setCurrentSlide] = useState(0);

    const nextSlide = () => {
        setCurrentSlide((prev) => (prev + 1) % images.length);
    };

    const prevSlide = () => {
        setCurrentSlide((prev) => (prev - 1 + images.length) % images.length);
    };

    const goToSlide = (index: number) => {
        setCurrentSlide(index);
    };

    return (
        <div className={`relative w-full overflow-hidden ${className}`}>
            {/* Slides */}
            <div
                className="flex transition-transform duration-700 ease-in-out"
                style={{ transform: `translateX(-${currentSlide * 100}%)` }}
                id="carousel-slides"
            >
                {images.map((image, index) => (
                    <div className="w-full flex-shrink-0 relative" key={index}>
                        <img
                            src={image.src}
                            alt={image.alt || ''}
                            className="w-full h-auto object-cover"
                        />
                    </div>
                ))}
            </div>

            {/* Controls */}
            <button
                onClick={prevSlide}
                className="text-center absolute top-1/2 left-4 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white p-2 rounded-full w-10"
                aria-label="Previous Slide"
            >
                &#10094;
            </button>
            <button
                onClick={nextSlide}
                className="text-center absolute top-1/2 right-4 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white p-2 rounded-full w-10"
                aria-label="Next Slide"
            >
                &#10095;
            </button>

            {/* Indicators */}
            <div
                className="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2"
                id="carousel-indicators"
            >
                {images.map((_, index) => (
                    <button
                        key={index}
                        onClick={() => goToSlide(index)}
                        className={`btn p-0 w-3 h-3 rounded-full ${
                            index === currentSlide ? 'bg-white opacity-100' : 'bg-white opacity-50'
                        }`}
                        aria-label={`Go to slide ${index + 1}`}
                    ></button>
                ))}
            </div>
        </div>
    );
};

export default Carousel;
