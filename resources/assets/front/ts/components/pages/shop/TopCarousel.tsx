import React, { FC } from "react";

// const items = [
//     {
//         src: "/assets/front/image/bunner_01.jpg",
//         altText: "Slide 1",
//         caption: "Slide 1",
//     },
//     {
//         src: "/assets/front/image/bunner_02.jpg",
//         altText: "Slide 2",
//         caption: "Slide 2",
//     },
//     {
//         src: "/assets/front/image/bunner_01.jpg",
//         altText: "Slide 3",
//         caption: "Slide 3",
//     },
//     {
//         src: "/assets/front/image/bunner_02.jpg",
//         altText: "Slide 4",
//         caption: "Slide 4",
//     },
// ];

const TopCarousel: FC = () => {
    // const [activeIndex, setActiveIndex] = useState(0);
    // const [animating, setAnimating] = useState(false);

    // const next = () => {
    //     if (animating) return;
    //     const nextIndex =
    //         activeIndex === items.length - 1 ? 0 : activeIndex + 1;
    //     setActiveIndex(nextIndex);
    // };
    //
    // const previous = () => {
    //     if (animating) return;
    //     const nextIndex =
    //         activeIndex === 0 ? items.length - 1 : activeIndex - 1;
    //     setActiveIndex(nextIndex);
    // };
    //
    // const goToIndex = (newIndex) => {
    //     if (animating) return;
    //     setActiveIndex(newIndex);
    // };
    //
    // const slides = items.map((item, index) => {
    //     return (
    //         <div
    //             onExiting={() => setAnimating(true)}
    //             onExited={() => setAnimating(false)}
    //             key={index}
    //         >
    //             <img src={item.src} alt={item.altText} />
    //             <div
    //                 captionText={item.caption}
    //                 captionHeader={item.caption}
    //             ></div>
    //         </div>
    //     );
    // });

    return <></>
    // return (
    //     <div activeIndex={activeIndex} next={next} previous={previous}>
    //         <div
    //             items={items}
    //
    //             items={items}
    //             activeIndex={activeIndex}
    //             onClickHandler={goToIndex}
    //         ></div>
    //         {slides}
    //         <div
    //             direction="prev"
    //             directionText="Previous"
    //             onClickHandler={previous}
    //         ></div>
    //         <div
    //             direction="next"
    //             directionText="Next"
    //             onClickHandler={next}
    //         ></div>
    //     </div>
    // );
};

export default TopCarousel;
